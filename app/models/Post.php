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
    $this->db->query('SELECT post.id as post_id,
                             post.user_id as user_id,
                             user.email as user_email,
                             profile.name as user_name,
                             post.body as post_body,
                             post.updated_at as post_stamp
                      FROM posts post
                      INNER JOIN users user
                            ON post.user_id = user.id
                      INNER JOIN profiles profile
                            ON profile.user_id = user.id
                      WHERE post.id=:id');
    $this->db->bind(':id', $id);

    return $this->db->single();
  }

  public function getPosts() {
    $this->db->query('SELECT post.id as post_id,
                             post.user_id as user_id,
                             user.email as user_email,
                             profile.name as user_name,
                             post.body as post_body,
                             post.updated_at as post_stamp
                      FROM posts post
                      INNER JOIN users user
                            ON post.user_id = user.id
                      INNER JOIN profiles profile
                            ON profile.user_id = user.id'); // need to do paging here eventually

    return $this->db->resultSet();
  }

  public function getPostsByUserId($user_id) {
    $this->db->query('SELECT post.id as post_id,
                             post.user_id as user_id,
                             user.email as user_email,
                             profile.name as user_name,
                             post.body as post_body,
                             post.updated_at as post_stamp
                      FROM posts post
                      INNER JOIN users user
                            ON post.user_id = user.id
                      INNER JOIN profiles profile
                            ON profile.user_id = user.id
                      WHERE post.user_id=:user_id'); // need to do paging here eventually
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
