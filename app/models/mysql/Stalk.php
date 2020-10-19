<?php

class Stalk {
  private $db;

  public function __construct() {
    $this->db = new MySQL();
  }

  public function getStalkees() {
    $this->db->query('SELECT stalkee FROM stalk WHERE stalker=:stalker');
    $this->db->bind(':stalker', $_SESSION['user_id']);

    $results = $this->db->resultSet();

    return $results ? $results : false ;
  }

  public function isStalking($user_id) {
    $this->db->query('SELECT stalkee FROM stalk WHERE stalker=:stalker AND stalkee=:stalkee');
    $this->db->bind(':stalker', $_SESSION['user_id']);
    $this->db->bind(':stalkee', $user_id);

    $results = $this->db->resultSet();

    return $this->db->rowCount();
  }

  public function isStalkable($user_id) {
    $this->db->query('SELECT * FROM prefs WHERE user_id=:user_id and stalkable=1');
    $this->db->bind(':user_id', $user_id);

    $row = $this->db->single();

    return $row ? true : false;
  }

  public function startStalking($user_id) {
    $this->db->query('INSERT INTO stalk (stalker, stalkee) VALUES (:stalker, :stalkee)');
    $this->db->bind(':stalker', $_SESSION['user_id']);
    $this->db->bind(':stalkee', $user_id);

    return ($this->db->execute() ? true : false);
  }

  public function stopStalking($user_id) {
    $this->db->query('DELETE FROM stalk WHERE stalker=:stalker AND stalkee=:stalkee');
    $this->db->bind(':stalker', $_SESSION['user_id']);
    $this->db->bind(':stalkee', $user_id);

    return ($this->db->execute() ? true : false);
  }
}
