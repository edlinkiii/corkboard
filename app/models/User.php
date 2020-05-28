<?php

class User {
  private $db;

  public function __construct() {
    $this->db = new Database();
  }

  public function login($username, $password) {
    $this->db->query('SELECT user.id as id,
                             user.username as username,
                             user.email as email,
                             user.password as password,
                             profile.name as name,
                             profile.pic as pic
                      FROM users user
                      INNER JOIN profiles profile
                      ON profile.user_id = user.id
                      WHERE user.username = :username');
    $this->db->bind(':username', $username);

    $row = $this->db->single();

    if(!$row) return false;

    if($password == $row->password) return $row; // for testing...

    $hashed_password = $row->password;
    return password_verify($password, $hashed_password) ? $row : false ;
  }

  public function signup($data) {
    $this->db->query('INSERT INTO users (username, email, password) values (:username, :email, :password)');
    $this->db->bind(':username', $data['username']);
    $this->db->bind(':email', $data['email']);
    $this->db->bind(':password', password_hash($data['password'], PASSWORD_DEFAULT));

    if($this->db->execute()) {
      $user_id = $this->db->lastInsertId();

      $this->db->query('INSERT INTO prefs (user_id) values (:user_id)');
      $this->db->bind(':user_id', $user_id);
      $this->db->execute();

      $this->db->query('INSERT INTO profiles (name, user_id, pic) values (:name, :user_id, :pic)');
      $this->db->bind(':name', $data['username']);
      $this->db->bind(':user_id', $user_id);
      $this->db->bind(':pic', 'placeholder.png');

      return ($this->db->execute()) ? true : false ;
    }
    return false;
  }

  public function checkPassword($password) {
    $this->db->query('SELECT password FROM users WHERE id=:user_id');
    $this->db->bind(':user_id', $_SESSION['user_id']);

    $row = $this->db->single();

    if(!$row) return false;

    if($password == $row->password) return true; // for testing...

    $hashed_password = $row->password;
    return password_verify($password, $hashed_password) ? true : false ;
  }

  public function changePassword($password) {
    $this->db->query('UPDATE users SET password=:password WHERE id=:user_id');
    $this->db->bind(':user_id', $_SESSION['user_id']);
    $this->db->bind(':password', password_hash($password, PASSWORD_DEFAULT));

    return ($this->db->execute()) ? true : false ;
  }

  public function getUserID($username) {
    $this->db->query('SELECT id FROM users WHERE username = :username');
    $this->db->bind(':username', $username);

    $row = $this->db->single();

    return ($row) ? $row->id : 0 ;
  }

  public function findUserByEmail($email) {
    $this->db->query('SELECT * FROM users WHERE email = :email');
    $this->db->bind(':email', $email);

    $row = $this->db->single();

    return ($this->db->rowCount() > 0) ? true : false ;
  }

  public function emailInUse($email) {
    $this->db->query('SELECT * FROM users WHERE email = :email');
    $this->db->bind(':email', $email);

    $row = $this->db->single();

    return ($this->db->rowCount() > 0) ? true : false ;
  }

  public function usernameInUse($username) {
    $this->db->query('SELECT * FROM users WHERE username = :username');
    $this->db->bind(':username', $username);

    $row = $this->db->single();

    return ($this->db->rowCount() > 0) ? true : false ;
  }

  public function searchUsersByName($name) {
    $this->db->query('SELECT DISTINCT p.user_id AS id,
                             u.username AS username,
                             p.name AS name
                      FROM profiles p
                      INNER JOIN users u
                        ON u.id = p.user_id
                      WHERE u.id > 0
                        AND (p.name LIKE :name OR u.username LIKE :name)
                      ORDER BY name');
    $this->db->bind(':name', "%$name%");

    return $this->db->resultSet();
  }
}
