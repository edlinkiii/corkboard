<?php

class Profile {
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

  public function getProfileByEmail($email) {
    $this->db->query('SELECT * FROM users WHERE email = :email');
    $this->db->bind(':email', $email);

    $user_row = $this->db->single();

    $this->db->query('SELECT * FROM profiles WHERE user_id = :user_id');
    $this->db->bind(':user_id', $user_row->user_id);

    $profile_row = $this->db->single();

    return ($this->db->rowCount() > 0) ? $profile_row : false ;
  }

  public function getProfileByUserId($user_id) {
    $this->db->query('SELECT * FROM profiles WHERE user_id = :user_id');
    $this->db->bind(':user_id', $user_id);

    $row = $this->db->single();

    return ($this->db->rowCount() > 0) ? $row : false ;
  }
}
