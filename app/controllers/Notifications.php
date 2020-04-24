<?php

class Notifications extends Controller {
  public function __construct() {
    if(!isset($_SESSION['user_id'])) {
      redirect('users/login');
    }
    $this->notificationModel = $this->model('Notification');
    $this->postModel = $this->model('Post');
    $this->reactionModel = $this->model('Reaction');
    $_SESSION['active_link'] = '';
  }

  public function default() {
    return $this->show(null);
  }

  public function show() {
    $_SESSION['active_link'] = 'notifications';

    $data = [
      'unseen' => $this->notificationModel->getUnseenNotifications(),
      'seen' => $this->notificationModel->getSeenNotifications(),
    ];

    $this->view('notifications/list', $data);
  }

  public function testAdd($post_id) {
    $user_id = $this->postModel->getPostOwner($post_id);
    $id = $this->notificationModel->addNotification($user_id, $post_id, 1);
    die($id);
  }
}
