<?php

class User {
  private $db;

  public function __construct() {
    $this->db = new Database();
  }

  public function login($email, $password) {
    $this->db->query('SELECT user.id as id,
                             user.email as email,
                             user.password as password,
                             profile.name as name
                      FROM users user
                      INNER JOIN profiles profile
                      ON profile.user_id = user.id
                      WHERE user.email = :email');
    $this->db->bind(':email', $email);

    $row = $this->db->single();

    if(!$row) return false;

    $hashed_password = $row->password;
    return password_verify($password, $hashed_password) ? $row : false ;
  }

  public function signup($data) {
    $this->db->query('INSERT INTO users (email, password) values (:email, :password)');
    $this->db->bind(':email', $data['email']);
    $this->db->bind(':password', password_hash($data['password'], PASSWORD_DEFAULT));

    if($this->db->execute()) {
      $user_id = $this->db->lastInsertId();

      $this->db->query('INSERT INTO profiles (name, user_id) values (:name, :user_id)');
      $this->db->bind(':name', $data['name']);
      $this->db->bind(':user_id', $user_id);

      return ($this->db->execute()) ? true : false ;
    }
    return false;
  }

  public function findUserByEmail($email) {
    $this->db->query('SELECT * FROM users WHERE email = :email');
    $this->db->bind(':email', $email);

    $row = $this->db->single();

    return ($this->db->rowCount() > 0) ? true : false ;
  }
}
