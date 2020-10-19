<?php

class Reaction {
  private $db;

  public function __construct() {
    $this->db = new MySQL();
  }

  public function getReactionTotal($post_id) {
    $this->db->query('SELECT SUM(r.value) AS total
                      FROM post_reactions pr
                      INNER JOIN reactions r
                          ON pr.reaction_id = r.id
                      WHERE pr.post_id = :post_id');
    $this->db->bind(':post_id', $post_id);

    return $this->db->single();
  }

  public function setReaction($post_id, $reaction_id) {
    $this->db->query('DELETE FROM post_reactions WHERE post_id=:post_id AND user_id=:user_id');
    $this->db->bind(':post_id', $post_id);
    $this->db->bind(':user_id', $_SESSION['user_id']);
    $this->db->execute();

    if($reaction_id > 0) {
      $this->db->query('INSERT INTO post_reactions (post_id, user_id, reaction_id)
                                    VALUES (:post_id, :user_id, :reaction_id)');
      $this->db->bind(':post_id', $post_id);
      $this->db->bind(':reaction_id', $reaction_id);
      $this->db->bind(':user_id', $_SESSION['user_id']);
      $this->db->execute();
    }

    return $this->getReactionTotal($post_id);
  }

  public static function getReactionConfig() {
    $my_db = new MySQL();
    $my_db->query('SELECT * FROM reactions ORDER BY id');
    $my_db->execute();
    return $my_db->resultSet();
  }
}
