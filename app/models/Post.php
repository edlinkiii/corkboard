<?php

class Post {
  private $db;

  public function __construct() {
    $this->db = new Database();
  }

  // C -- create
  public function addPost($body) {
    // insert post
    $this->db->query('INSERT INTO posts (user_id, body) values (:user_id, :body)');
    $this->db->bind(':user_id', $_SESSION['user_id']);
    $this->db->bind(':body', $body);

    // return new id or false
    return ($this->db->execute()) ? $this->db->lastInsertId() : false ;
  }

  // R -- read
  public function getPost($id) {
    $this->db->query('SELECT post.id AS post_id,
                             post.user_id AS user_id,
                             user.email AS user_email,
                             prof.name AS user_name,
                             prof.pic AS user_pic,
                             post.body AS post_body,
                             post.updated_at AS post_stamp,
                             react.total AS post_reaction,
                             mine.reaction AS my_reaction
                      FROM posts post
                      INNER JOIN users user
                            ON post.user_id = user.id
                      INNER JOIN prefs pref
                            ON post.user_id = pref.user_id
                      INNER JOIN profiles prof
                            ON prof.user_id = user.id
                      LEFT OUTER JOIN (
                                SELECT SUM(r.value) AS total,
                                       pr.post_id AS post_id
                                FROM post_reactions pr
                                INNER JOIN reactions r
                                    ON pr.reaction_id = r.id
                                WHERE pr.post_id = :id
                      ) react
                            ON react.post_id = post.id
                      LEFT OUTER JOIN (
                                SELECT r.value AS reaction,
                                       pr.post_id AS post_id
                                FROM post_reactions pr
                                INNER JOIN reactions r
                                    ON pr.reaction_id = r.id
                                WHERE pr.post_id = :id
                                    AND pr.user_id = :user_id
                      ) mine
                            ON mine.post_id = post.id
                      WHERE post.id = :id
                            AND (pref.public = 1 OR post.user_id = :user_id)');
    $this->db->bind(':id', $id);
    $this->db->bind(':user_id', (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0));

    // $this->db->dump();

    $row = $this->db->single();

    return $row ? $row : false;
  }

  public function getPosts() {
    $this->db->query('SELECT post.id AS post_id,
                             post.user_id AS user_id,
                             user.email AS user_email,
                             prof.name AS user_name,
                             prof.pic AS user_pic,
                             post.body AS post_body,
                             post.updated_at AS post_stamp,
                             react.total AS post_reaction,
                             mine.reaction AS my_reaction
                      FROM posts post
                      INNER JOIN users user
                            ON post.user_id = user.id
                      INNER JOIN prefs prefs
                            ON post.user_id = prefs.user_id
                      INNER JOIN profiles prof
                            ON prof.user_id = user.id
                            LEFT OUTER JOIN (
                                SELECT SUM(r.value) AS total,
                                       pr.post_id AS post_id
                                FROM post_reactions pr
                                INNER JOIN reactions r
                                    ON pr.reaction_id = r.id
                      ) react
                            ON react.post_id = post.id
                      LEFT OUTER JOIN (
                                SELECT r.value AS reaction,
                                       pr.post_id AS post_id
                                FROM post_reactions pr
                                INNER JOIN reactions r
                                    ON pr.reaction_id = r.id
                                WHERE pr.user_id = :user_id
                      ) mine
                            ON mine.post_id = post.id
                      WHERE (prefs.public=1 OR post.user_id=:user_id)
                      ORDER BY post.updated_at DESC'); // need to do paging here eventually
    $this->db->bind(':user_id', (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0));

    return $this->db->resultSet();
  }

  public function stalkPosts() {
    $this->db->query('SELECT post.id as post_id,
                             post.user_id as user_id,
                             user.email as user_email,
                             profile.name as user_name,
                             profile.pic as user_pic,
                             post.body as post_body,
                             post.updated_at as post_stamp,
                             react.total AS post_reaction,
                             mine.reaction AS my_reaction
                      FROM posts post
                      INNER JOIN users user
                            ON post.user_id = user.id
                      INNER JOIN prefs prefs
                            ON post.user_id = prefs.user_id
                      INNER JOIN profiles profile
                            ON profile.user_id = user.id
                            INNER JOIN profiles prof
                            ON prof.user_id = user.id
                            LEFT OUTER JOIN (
                                SELECT SUM(r.value) AS total,
                                       pr.post_id AS post_id
                                FROM post_reactions pr
                                INNER JOIN reactions r
                                    ON pr.reaction_id = r.id
                      ) react
                            ON react.post_id = post.id
                      LEFT OUTER JOIN (
                                SELECT r.value AS reaction,
                                       pr.post_id AS post_id
                                FROM post_reactions pr
                                INNER JOIN reactions r
                                    ON pr.reaction_id = r.id
                                WHERE pr.user_id = :stalker
                      ) mine
                            ON mine.post_id = post.id
                      WHERE (prefs.stalkable=1 AND post.user_id IN (
                            SELECT stalkee FROM stalk WHERE stalker=:stalker
                      ))
                      ORDER BY post.updated_at DESC'); // need to do paging here eventually
    $this->db->bind(':stalker', $_SESSION['user_id']);

    return $this->db->resultSet();
  }

  public function getPostsByUserId($user_id) {
    $this->db->query('SELECT post.id as post_id,
                             post.user_id as user_id,
                             user.email as user_email,
                             profile.name as user_name,
                             profile.pic as user_pic,
                             post.body as post_body,
                             post.updated_at as post_stamp
                      FROM posts post
                      INNER JOIN users user
                            ON post.user_id = user.id
                      INNER JOIN prefs prefs
                            ON post.user_id = prefs.user_id
                      INNER JOIN profiles profile
                            ON profile.user_id = user.id
                      WHERE post.user_id=:user_id
                      ORDER BY post.updated_at DESC'); // need to do paging here eventually
    $this->db->bind(':user_id', $user_id);

    return $this->db->resultSet();
  }

  // U -- update
  public function editPost($data) {
    $this->db->query('UPDATE posts SET body=:body, updated_at=now() WHERE id=:id and user_id=:user_id');
    $this->db->bind(':body', $data['body']);
    $this->db->bind(':id', $data['id']);
    $this->db->bind(':user_id', $_SESSION['user_id']);

    return ($this->db->execute()) ? $data['id'] : false ;
  }

  // D -- delete
  public function removePost($id) {
    $this->db->query('DELETE FROM posts WHERE id=:id and user_id=:user_id');
    $this->db->bind(':id', $id);
    $this->db->bind(':user_id', $_SESSION['user_id']);

    return ($this->db->execute()) ? true : false ;
  }
}
