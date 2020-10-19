<?php

class Prefs {
  private $db;

  public function __construct() {
    $this->db = new Database();
  }

  public function getPrefs($user_id) {
    $this->db->query('SELECT * FROM prefs WHERE user_id = :user_id');
    $this->db->bind(':user_id', $user_id);

    $row = $this->db->single();

    return ($row) ? $row : false;
  }

  public function updatePrefs($data) {
    $this->db->query('UPDATE prefs
                      SET public=:public,
                          stalkable=:stalkable,
                          show_birthdate=:show_birthdate,
                          show_location=:show_location
                      WHERE user_id = :user_id');
    $this->db->bind(':public', $data['public']);
    $this->db->bind(':stalkable', $data['stalkable']);
    $this->db->bind(':show_birthdate', $data['show_birthdate']);
    $this->db->bind(':show_location', $data['show_location']);
    $this->db->bind(':user_id', $_SESSION['user_id']);

    return ($this->db->execute() ? true : false);
  }
}
