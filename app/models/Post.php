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
    $this->db->query('SELECT p.id as post_id,
                             p.user_id as user_id,
                             u.name as user_name,
                             p.body as post_body,
                             p.updated_at as post_stamp
                      FROM posts p 
                      INNER JOIN users u
                      ON p.user_id = u.id
                      WHERE p.id=:id');
    $this->db->bind(':id', $id);

    return $this->db->single();
  }

  public function getPosts() {
    $this->db->query('SELECT p.id as post_id,
                              p.user_id as user_id,
                              u.name as user_name,
                              p.body as post_body,
                              p.updated_at as post_stamp
                      FROM posts p 
                      INNER JOIN users u
                      ON p.user_id = u.id'); // need to do paging here eventually

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

  // public function login($email, $password) {
  //   $this->db->query('SELECT * FROM users WHERE email = :email');
  //   $this->db->bind(':email', $email);

  //   $row = $this->db->single();

  //   if(!$row) return false;

  //   $hashed_password = $row->password;
  //   return password_verify($password, $hashed_password) ? $row : false ;
  // }

  // public function signup($data) {
  //   $this->db->query('INSERT INTO users (name, email, password) values (:name, :email, :password)');
  //   $this->db->bind(':name', $data['name']);
  //   $this->db->bind(':email', $data['email']);
  //   $this->db->bind(':password', password_hash($data['password'], PASSWORD_DEFAULT));

  //   return ($this->db->execute()) ? true : false ;
  // }

  // public function findUserByEmail($email) {
  //   $this->db->query('SELECT * FROM users WHERE email = :email');
  //   $this->db->bind(':email', $email);

  //   $row = $this->db->single();

  //   return ($this->db->rowCount() > 0) ? true : false ;
  // }
}
