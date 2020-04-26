<?php

class Notification {
  private $db;

  public function __construct() {
    $this->db = new Database();
  }

  // C - create
  public function addNotification($user_id, $post_id, $type_id) {
    $this->db->query('INSERT INTO notifications (user_id, post_id, type_id)
                                  VALUES (:user_id, :post_id, :type_id)');
    $this->db->bind(':user_id', $user_id);
    $this->db->bind(':post_id', $post_id);
    $this->db->bind(':type_id', $type_id);

    return ($this->db->execute()) ? $this->db->lastInsertId() : 0 ;
  }
  // R - read
  public function getUnseenNotifications() {
    $this->db->query('SELECT n.post_id AS post_id,
                             n.type_id AS type_id,
                             COUNT(n.type_id) AS type_count,
                             n.seen_at AS notification_seen,
                             nt.name AS type_name,
                             p.body AS post_body,
                             p.created_at AS post_created,
                             p.reply_to_id AS post_is_reply
                      FROM notifications n
                      INNER JOIN notification_types nt
                            ON nt.id = n.type_id
                      INNER JOIN posts p
                            ON p.id = n.post_id
                      WHERE n.user_id=:user_id
                      AND n.seen_at IS NULL
                      GROUP BY n.post_id, n.type_id
                      ORDER BY n.created_at');
    $this->db->bind(':user_id', $_SESSION['user_id']);

    return $this->db->resultSet();
  }
  public function getSeenNotifications() {
    $this->db->query('SELECT n.post_id AS post_id,
                             n.type_id AS type_id,
                             COUNT(n.type_id) AS type_count,
                             n.seen_at AS notification_seen,
                             nt.name AS type_name,
                             p.body AS post_body,
                             p.created_at AS post_created,
                             p.reply_to_id AS post_is_reply
                      FROM notifications n
                      INNER JOIN notification_types nt
                          ON nt.id = n.type_id
                      INNER JOIN posts p
                            ON p.id = n.post_id
                      WHERE n.user_id=:user_id
                      AND n.seen_at > 0
                      GROUP BY n.post_id, n.type_id
                      ORDER BY n.created_at');
    $this->db->bind(':user_id', $_SESSION['user_id']);

    return $this->db->resultSet();
  }
  // U - update
  public function markNotificationSeen($id) {
    $this->db->query('UPDATE notifications SET seen_at = NOW() WHERE id=:id');
    $this->db->bind(':id',$id);
  }
  // D - delete

  // utility methods
  public function countUnseenNotifications() {
    $this->db->query('SELECT post_id, type_id, COUNT(type_id) AS type_count
                      FROM notifications
                      WHERE user_id=:user_id AND seen_at IS NULL
                      GROUP BY post_id, type_id');
    $this->db->bind(':user_id', $_SESSION['user_id']);

    return $this->db->resultSet();

  }
}
