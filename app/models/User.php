<?php

class User {
  private $db;

  public function __construct() {
    $this->db = new Database();
  }

  public function login($email, $password) {
    $this->db->query('SELECT * FROM users WHERE email = :email');
    $this->db->bind(':email', $email);

    $row = $this->db->single();

    if(!$row) return false;

    $hashed_password = $row->password;
    return password_verify($password, $hashed_password) ? $row : false ;
  }

  public function signup($data) {
    $this->db->query('INSERT INTO users (name, email, password) values (:name, :email, :password)');
    $this->db->bind(':name', $data['name']);
    $this->db->bind(':email', $data['email']);
    $this->db->bind(':password', password_hash($data['password'], PASSWORD_DEFAULT));

    return ($this->db->execute()) ? true : false ;
  }

  public function findUserByEmail($email) {
    $this->db->query('SELECT * FROM users WHERE email = :email');
    $this->db->bind(':email', $email);

    $row = $this->db->single();

    return ($this->db->rowCount() > 0) ? true : false ;
  }
}
