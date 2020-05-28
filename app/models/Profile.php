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

  // public function getProfileByEmail($email) {
  //   $this->db->query('SELECT * FROM users WHERE email = :email');
  //   $this->db->bind(':email', $email);

  //   $user_row = $this->db->single();

  //   $this->db->query('SELECT * FROM profiles WHERE user_id = :user_id');
  //   $this->db->bind(':user_id', $user_row->user_id);

  //   $profile_row = $this->db->single();

  //   return ($this->db->rowCount() > 0) ? $profile_row : false ;
  // }

  public function getProfileByUserId($user_id) {
    $this->db->query('SELECT  p.name AS name,
                              p.birthdate AS birthdate,
                              p.bio AS bio,
                              p.pic AS pic,
                              p.created_at AS created_at,
                              u.id AS user_id,
                              u.username AS user_username
                      FROM profiles p
                      INNER JOIN users u
                            ON p.user_id = u.id
                      WHERE p.user_id = :user_id');
    $this->db->bind(':user_id', $user_id);

    $row = $this->db->single();

    return ($this->db->rowCount() > 0) ? $row : false ;
  }

  public function updateProfile($data) {
    $this->db->query('UPDATE profiles
                      SET name=:name,
                          birthdate=:birthdate,
                          bio=:bio
                      WHERE user_id = :user_id');
    $this->db->bind(':name', $data['name']);
    $this->db->bind(':birthdate', $data['birthdate']);
    $this->db->bind(':bio', $data['bio']);
    $this->db->bind(':user_id', $_SESSION['user_id']);

    return ($this->db->execute() ? true : false);
  }

  public function updateProfilePic($ext) {
    $this->db->query('UPDATE profiles
                      SET pic=:pic_ext
                      WHERE user_id = :user_id');
    $this->db->bind(':pic_ext', $ext);
    $this->db->bind(':user_id', $_SESSION['user_id']);

    return ($this->db->execute() ? true : false);
  }
}
