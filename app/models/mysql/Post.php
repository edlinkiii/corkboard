<?php

class Post {
  private $db;

  public function __construct() {
    $this->db = new MySQL();
  }

  // C -- create
  public function addPost($body, $img) {
    // insert post
    $this->db->query('INSERT INTO posts (user_id, body, img) values (:user_id, :body, :img)');
    $this->db->bind(':user_id', $_SESSION['user_id']);
    $this->db->bind(':body', $body);
    $this->db->bind(':img', $img);

    // return new id or false
    return ($this->db->execute()) ? $this->db->lastInsertId() : false ;
  }

  public function addReply($body, $post_id) {
    // insert post
    $this->db->query('INSERT INTO posts (user_id, reply_to_id, body) values (:user_id, :post_id, :body)');
    $this->db->bind(':user_id', $_SESSION['user_id']);
    $this->db->bind(':post_id', $post_id);
    $this->db->bind(':body', $body);

    $this->db->execute();

    return $this->getReplyCount($post_id);
  }

  // R -- read
  public function getPost($id) {
    $this->db->query('SELECT post.id AS post_id,
                             post.user_id AS user_id,
                             user.username AS user_username,
                             user.email AS user_email,
                             prof.name AS user_name,
                             prof.pic AS user_pic,
                             post.body AS post_body,
                             post.img AS post_img,
                             post.reply_to_id AS post_reply_to_id,
                             post.updated_at AS post_stamp,
                             react.total AS post_reaction,
                             pr.reaction_id AS my_reaction,
                             reply.replies AS post_reply_count,
                             my_reply.replies AS my_reply_count,
                             reply_info.user_id AS post_reply_user_id,
                             reply_info.user_name AS post_reply_user_name,
                             favs.count AS is_favorite
                      FROM posts post
                      INNER JOIN users user
                            ON post.user_id = user.id
                      INNER JOIN prefs pref
                            ON post.user_id = pref.user_id
                      INNER JOIN profiles prof
                            ON prof.user_id = user.id
                      LEFT OUTER JOIN post_reactions pr
                            ON pr.post_id = post.id AND pr.user_id = :user_id
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
                                SELECT COUNT(*) AS replies,
                                       reply_to_id
                                FROM posts
                                WHERE reply_to_id=:id
                      ) reply
                            ON reply.reply_to_id = post.id
                      LEFT OUTER JOIN (
                                SELECT COUNT(*) AS replies,
                                       reply_to_id
                                FROM posts
                                WHERE reply_to_id=:id
                                    AND user_id=:user_id
                      ) my_reply
                            ON my_reply.reply_to_id = post.id
                      LEFT OUTER JOIN (
                                SELECT post.user_id AS user_id,
                                       post.id as post_id,
                                       prof.name as user_name
                                FROM posts post
                                INNER JOIN profiles prof
                                    ON post.user_id = prof.user_id
                      ) reply_info
                            ON reply_info.post_id = post.reply_to_id
                      LEFT OUTER JOIN (
                              SELECT COUNT(*) AS count, post_id
                              FROM favorites
                              WHERE user_id=:user_id AND post_id=:id
                              GROUP BY post_id
                      ) favs ON favs.post_id = post.id
                      WHERE post.id = :id
                            AND (pref.public = 1 OR post.user_id = :user_id)');
    $this->db->bind(':id', $id);
    $this->db->bind(':user_id', (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0));

    // $this->db->dump();

    // $row = $this->db->single();
    $row = $this->db->resultSet();

    return $row ? $row : false;
  }

  public function getReplies($post_id) {
    $this->db->query('SELECT post.id AS post_id,
                             post.user_id AS user_id,
                             user.username AS user_username,
                             user.email AS user_email,
                             prof.name AS user_name,
                             prof.pic AS user_pic,
                             post.body AS post_body,
                             post.img AS post_img,
                             post.updated_at AS post_stamp,
                             post.reply_to_id AS post_reply_to_id,
                             react.total AS post_reaction,
                             pr.reaction_id AS my_reaction,
                             reply.replies AS post_reply_count,
                             my_reply.replies AS my_reply_count,
                             reply_info.user_id AS post_reply_user_id,
                             reply_info.user_name AS post_reply_user_name,
                             favs.count AS is_favorite
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
                                GROUP BY pr.post_id
                      ) react
                            ON react.post_id = post.id
                      LEFT OUTER JOIN post_reactions pr
                            ON pr.post_id = post.id AND pr.user_id = :user_id
                      LEFT OUTER JOIN (
                                SELECT COUNT(*) AS replies,
                                       reply_to_id
                                FROM posts
                                WHERE reply_to_id=:post_id
                      ) reply
                            ON reply.reply_to_id = post.id
                      LEFT OUTER JOIN (
                                SELECT COUNT(*) AS replies,
                                       reply_to_id
                                FROM posts
                                WHERE reply_to_id=:post_id
                                    AND user_id=:user_id
                      ) my_reply
                            ON my_reply.reply_to_id = post.id
                      LEFT OUTER JOIN (
                                SELECT post.user_id AS user_id,
                                       post.id as post_id,
                                       prof.name as user_name
                                FROM posts post
                                INNER JOIN profiles prof
                                    ON post.user_id = prof.user_id
                      ) reply_info
                            ON reply_info.post_id = post.reply_to_id
                      LEFT OUTER JOIN (
                              SELECT COUNT(*) AS count, post_id
                              FROM favorites
                              WHERE user_id=:user_id
                              GROUP BY post_id
                      ) favs ON favs.post_id = post.id
                      WHERE (prefs.public=1 OR post.user_id=:user_id)
                            AND post.reply_to_id = :post_id
                      ORDER BY post.created_at DESC
                      LIMIT :start, :end');
    $this->db->bind(':user_id', (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0));
    $this->db->bind(':post_id', $post_id);
    $this->db->bind(':start', ($_SESSION['more_page'] * POSTS_PER_PAGE));
    $this->db->bind(':end', POSTS_PER_PAGE);

    return $this->db->resultSet();
  }

  public function getPosts() {
    $this->db->query('SELECT post.id AS post_id,
                             post.user_id AS user_id,
                             user.username AS user_username,
                             user.email AS user_email,
                             prof.name AS user_name,
                             prof.pic AS user_pic,
                             post.body AS post_body,
                             post.img AS post_img,
                             post.updated_at AS post_stamp,
                             post.reply_to_id AS post_reply_to_id,
                             react.total AS post_reaction,
                             pr.reaction_id AS my_reaction,
                             reply.replies AS post_reply_count,
                             my_reply.replies AS my_reply_count,
                             reply_info.user_id AS post_reply_user_id,
                             reply_info.user_name AS post_reply_user_name,
                             favs.count AS is_favorite
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
                                GROUP BY pr.post_id
                      ) react
                            ON react.post_id = post.id
                      LEFT OUTER JOIN post_reactions pr
                            ON pr.post_id = post.id AND pr.user_id = :user_id
                      LEFT OUTER JOIN (
                                SELECT COUNT(*) AS replies,
                                       reply_to_id
                                FROM posts
                                GROUP BY reply_to_id
                      ) reply
                            ON reply.reply_to_id = post.id
                      LEFT OUTER JOIN (
                                SELECT COUNT(*) AS replies,
                                       reply_to_id
                                FROM posts
                                WHERE user_id=:user_id
                                GROUP BY reply_to_id
                      ) my_reply
                            ON my_reply.reply_to_id = post.id
                      LEFT OUTER JOIN (
                                SELECT post.user_id AS user_id,
                                       post.id as post_id,
                                       prof.name as user_name
                                FROM posts post
                                INNER JOIN profiles prof
                                    ON post.user_id = prof.user_id
                      ) reply_info
                            ON reply_info.post_id = post.reply_to_id
                      LEFT OUTER JOIN (
                              SELECT COUNT(*) AS count, post_id
                              FROM favorites
                              WHERE user_id=:user_id
                              GROUP BY post_id
                      ) favs ON favs.post_id = post.id
                      WHERE (prefs.public=1 OR post.user_id=:user_id)
                          AND post.reply_to_id IS NULL
                      ORDER BY post.created_at DESC
                      LIMIT :start, :end');
    $this->db->bind(':user_id', (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0));
    $this->db->bind(':start', ($_SESSION['more_page'] * POSTS_PER_PAGE));
    $this->db->bind(':end', POSTS_PER_PAGE);

    return $this->db->resultSet();
  }

  public function stalkPosts() {
    $this->db->query('SELECT post.id as post_id,
                             post.user_id as user_id,
                             user.username AS user_username,
                             user.email as user_email,
                             prof.name as user_name,
                             prof.pic as user_pic,
                             post.body as post_body,
                             post.img as post_img,
                             post.updated_at as post_stamp,
                             post.reply_to_id AS post_reply_to_id,
                             react.total AS post_reaction,
                             pr.reaction_id AS my_reaction,
                             reply.replies AS post_reply_count,
                             my_reply.replies AS my_reply_count,
                             reply_info.user_id AS post_reply_user_id,
                             reply_info.user_name AS post_reply_user_name,
                             favs.count AS is_favorite
                      FROM posts post
                      INNER JOIN users user
                            ON post.user_id = user.id
                      INNER JOIN prefs prefs
                            ON post.user_id = prefs.user_id
                      INNER JOIN profiles prof
                            ON prof.user_id = user.id
                      LEFT OUTER JOIN post_reactions pr
                            ON pr.post_id = post.id AND pr.user_id = :user_id
                      LEFT OUTER JOIN (
                                SELECT SUM(r.value) AS total,
                                       pr.post_id AS post_id
                                FROM post_reactions pr
                                INNER JOIN reactions r
                                    ON pr.reaction_id = r.id
                                GROUP BY pr.post_id
                      ) react
                            ON react.post_id = post.id
                      LEFT OUTER JOIN (
                                SELECT COUNT(*) AS replies,
                                       reply_to_id
                                FROM posts
                                GROUP BY reply_to_id
                      ) reply
                            ON reply.reply_to_id = post.id
                      LEFT OUTER JOIN (
                                SELECT COUNT(*) AS replies,
                                       reply_to_id
                                FROM posts
                                WHERE user_id=:user_id
                                GROUP BY reply_to_id
                      ) my_reply
                            ON my_reply.reply_to_id = post.id
                      LEFT OUTER JOIN (
                                SELECT post.user_id AS user_id,
                                       post.id as post_id,
                                       prof.name as user_name
                                FROM posts post
                                INNER JOIN profiles prof
                                    ON post.user_id = prof.user_id
                      ) reply_info
                            ON reply_info.post_id = post.reply_to_id
                      LEFT OUTER JOIN (
                              SELECT COUNT(*) AS count, post_id
                              FROM favorites
                              WHERE user_id=:user_id
                              GROUP BY post_id
                      ) favs ON favs.post_id = post.id
                      WHERE (prefs.stalkable=1 AND post.user_id IN (
                            SELECT stalkee FROM stalk WHERE stalker=:user_id
                      ))
                      ORDER BY post.created_at DESC
                      LIMIT :start, :end');
    $this->db->bind(':user_id', $_SESSION['user_id']);
    $this->db->bind(':start', ($_SESSION['more_page'] * POSTS_PER_PAGE));
    $this->db->bind(':end', POSTS_PER_PAGE);

    return $this->db->resultSet();
  }

  public function favoritePosts() {
    $this->db->query('SELECT post.id as post_id,
                             post.user_id as user_id,
                             user.username AS user_username,
                             user.email as user_email,
                             prof.name as user_name,
                             prof.pic as user_pic,
                             post.body as post_body,
                             post.img as post_img,
                             post.updated_at as post_stamp,
                             post.reply_to_id AS post_reply_to_id,
                             react.total AS post_reaction,
                             pr.reaction_id AS my_reaction,
                             reply.replies AS post_reply_count,
                             my_reply.replies AS my_reply_count,
                             reply_info.user_id AS post_reply_user_id,
                             reply_info.user_name AS post_reply_user_name,
                             favs.count AS is_favorite
                      FROM posts post
                      INNER JOIN users user
                            ON post.user_id = user.id
                      INNER JOIN prefs prefs
                            ON post.user_id = prefs.user_id
                      INNER JOIN profiles prof
                            ON prof.user_id = user.id
                      LEFT OUTER JOIN post_reactions pr
                            ON pr.post_id = post.id AND pr.user_id = :user_id
                      LEFT OUTER JOIN (
                                SELECT SUM(r.value) AS total,
                                       pr.post_id AS post_id
                                FROM post_reactions pr
                                INNER JOIN reactions r
                                    ON pr.reaction_id = r.id
                                GROUP BY pr.post_id
                      ) react
                            ON react.post_id = post.id
                      LEFT OUTER JOIN (
                                SELECT COUNT(*) AS replies,
                                       reply_to_id
                                FROM posts
                                GROUP BY reply_to_id
                      ) reply
                            ON reply.reply_to_id = post.id
                      LEFT OUTER JOIN (
                                SELECT COUNT(*) AS replies,
                                       reply_to_id
                                FROM posts
                                WHERE user_id=:user_id
                                GROUP BY reply_to_id
                      ) my_reply
                            ON my_reply.reply_to_id = post.id
                      LEFT OUTER JOIN (
                                SELECT post.user_id AS user_id,
                                       post.id as post_id,
                                       prof.name as user_name
                                FROM posts post
                                INNER JOIN profiles prof
                                    ON post.user_id = prof.user_id
                      ) reply_info
                            ON reply_info.post_id = post.reply_to_id
                      LEFT OUTER JOIN (
                              SELECT COUNT(*) AS count, post_id
                              FROM favorites
                              WHERE user_id=:user_id
                              GROUP BY post_id
                      ) favs ON favs.post_id = post.id
                      WHERE post.id IN (
                            SELECT post_id FROM favorites WHERE user_id=:user_id
                      )
                      ORDER BY post.created_at DESC
                      LIMIT :start, :end');
    $this->db->bind(':user_id', $_SESSION['user_id']);
    $this->db->bind(':start', ($_SESSION['more_page'] * POSTS_PER_PAGE));
    $this->db->bind(':end', POSTS_PER_PAGE);
    // $this->db->dump();

    return $this->db->resultSet();
  }

  public function getPostsByUserId($user_id) {
    $this->db->query('SELECT post.id as post_id,
                             post.user_id as user_id,
                             user.username AS user_username,
                             user.email as user_email,
                             prof.name as user_name,
                             prof.pic as user_pic,
                             post.body as post_body,
                             post.img as post_img,
                             post.updated_at as post_stamp,
                             post.reply_to_id AS post_reply_to_id,
                             react.total AS post_reaction,
                             pr.reaction_id AS my_reaction,
                             reply.replies AS post_reply_count,
                             my_reply.replies AS my_reply_count,
                             reply_info.user_id AS post_reply_user_id,
                             reply_info.user_name AS post_reply_user_name,
                             favs.count AS is_favorite
                      FROM posts post
                      INNER JOIN users user
                            ON post.user_id = user.id
                      INNER JOIN prefs prefs
                            ON post.user_id = prefs.user_id
                      INNER JOIN profiles prof
                            ON prof.user_id = user.id
                      LEFT OUTER JOIN post_reactions pr
                            ON pr.post_id = post.id AND pr.user_id = :my_user_id
                      LEFT OUTER JOIN (
                                SELECT SUM(r.value) AS total,
                                       pr.post_id AS post_id
                                FROM post_reactions pr
                                INNER JOIN reactions r
                                    ON pr.reaction_id = r.id
                                GROUP BY pr.post_id
                      ) react
                            ON react.post_id = post.id
                      LEFT OUTER JOIN (
                                SELECT r.value AS reaction,
                                       pr.post_id AS post_id
                                FROM post_reactions pr
                                INNER JOIN reactions r
                                    ON pr.reaction_id = r.id
                                WHERE pr.user_id = :my_user_id
                      ) mine
                            ON mine.post_id = post.id
                      LEFT OUTER JOIN (
                                SELECT COUNT(*) AS replies,
                                       reply_to_id
                                FROM posts
                                GROUP BY reply_to_id
                      ) reply
                            ON reply.reply_to_id = post.id
                      LEFT OUTER JOIN (
                                SELECT COUNT(*) AS replies,
                                       reply_to_id
                                FROM posts
                                WHERE user_id=:my_user_id
                                GROUP BY reply_to_id
                      ) my_reply
                            ON my_reply.reply_to_id = post.id
                      LEFT OUTER JOIN (
                                SELECT post.user_id AS user_id,
                                       post.id as post_id,
                                       prof.name as user_name
                                FROM posts post
                                INNER JOIN profiles prof
                                    ON post.user_id = prof.user_id
                      ) reply_info
                            ON reply_info.post_id = post.reply_to_id
                      LEFT OUTER JOIN (
                              SELECT COUNT(*) AS count, post_id
                              FROM favorites
                              WHERE user_id=:my_user_id
                              GROUP BY post_id
                      ) favs ON favs.post_id = post.id
                      WHERE post.user_id=:user_id
                      ORDER BY post.created_at DESC
                      LIMIT :start, :end');
    $this->db->bind(':user_id', $user_id);
    $this->db->bind(':my_user_id', (isset($_SESSION['user_id']) ?: 0));
    $this->db->bind(':start', ($_SESSION['more_page'] * POSTS_PER_PAGE));
    $this->db->bind(':end', POSTS_PER_PAGE);
    // $this->db->dump();

    return $this->db->resultSet();
  }

  public function getReplyCount($post_id) {
    $this->db->query('SELECT COUNT(*) AS total FROM posts WHERE reply_to_id=:post_id');
    $this->db->bind(':post_id', $post_id);

    return $this->db->single();

  }

  // U -- update
  public function editPost($data) {
    $this->db->query('UPDATE posts SET body=:body, img=:img, updated_at=now() WHERE id=:id and user_id=:user_id');
    $this->db->bind(':body', $data['body']);
    $this->db->bind(':img', $data['img']);
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

  public function getPostOwner($id) {
    $this->db->query('SELECT user_id FROM posts WHERE id=:id');
    $this->db->bind(':id',$id);

    return $this->db->single()->user_id;
  }
}
