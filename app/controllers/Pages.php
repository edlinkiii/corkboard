<?php

class Pages extends Controller {
  public function __construct() {
    $this->reactionModel = $this->model('Reaction');
  }

  public function default() {
    $this->index();
  }

  public function index() {
    redirect('posts/show');
  }

  public function about() {
    $data = [
      'title' => 'About C0rkē'
    ];
    $this->view('pages/about', $data);
  }

  public function permission() {
    $this->view('pages/permission');
  }
}
