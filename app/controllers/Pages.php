<?php

class Pages extends Controller {
  public function __construct() {
  }

  public function index() {
    redirect('posts/show');
  }

  public function about() {
    $data = [
      'title' => 'About Us'
    ];
    $this->view('pages/about', $data);
  }

  public function permission() {
    $this->view('pages/permission');
  }
}
