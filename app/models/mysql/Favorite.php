<?php

class Favorite {
  private $db;

  public function __construct() {
    $this->db = new MySQL();
  }

  public function isFavorite($post_id) {
    $this->db->query('SELECT COUNT(*) AS favorite FROM favorites
                      WHERE user_id=:user_id AND post_id=:post_id');
    $this->db->bind(':user_id', $_SESSION['user_id']);
    $this->db->bind(':post_id', $post_id);

    $result = $this->db->single();

    return $result->favorite;
  }

  public function listFavorites() {
    $this->db->query('SELECT post_id FROM favorites WHERE user_id=:user_id');
    $this->db->bind(':user_id', $_SESSION['user_id']);

    $rows = $this->db->resultSet();

    return $rows ? $rows : false;
  }

  public function addFavorite($post_id) {
    $this->db->query('INSERT INTO favorites (user_id, post_id) VALUES (:user_id, :post_id)');
    $this->db->bind(':user_id', $_SESSION['user_id']);
    $this->db->bind(':post_id', $post_id);

    // return new id or zero
    return ($this->db->execute()) ? $this->db->lastInsertId() : 0 ;
  }

  public function removeFavorite($post_id) {
    $this->db->query('DELETE FROM favorites WHERE user_id=:user_id AND post_id=:post_id');
    $this->db->bind(':user_id', $_SESSION['user_id']);
    $this->db->bind(':post_id', $post_id);

    // return true or false
    return ($this->db->execute()) ? true : false ;
  }
}
