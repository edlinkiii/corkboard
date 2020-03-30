<?php

class User {
  private $db;

  public function __construct() {
    $this->db = new Database();
  }

  public function login($email, $password) {
    // echo $email . ' :: ' . $password . '<br>';
    $this->db->query('SELECT * FROM users WHERE email = :email');
    $this->db->bind(':email', $email);

    $row = $this->db->single();

    if(!$row) return false;

    // $hashed_password = $row->password;
    // return password_verify($password, $hashed_password) ? $row : false ;

    return $password == $row->password ? $row : false ;
  }

  public function findUserByEmail($email) {
    $this->db->query('SELECT * FROM users WHERE email = :email');
    $this->db->bind(':email', $email);

    $row = $this->db->single();

    return ($this->db->rowCount() > 0) ? true : false ;
  }
}
