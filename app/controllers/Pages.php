<?php

class Pages extends Controller {
  public function __construct() {
  }

  public function index() {
    $data = [
      'posts' => [
        [
          'user_id' => 1,
          'user_name' => 'edlinkiii',
          'body' => 'Test'
        ],
        [
          'user_id' => 1,
          'user_name' => 'edlinkiii',
          'body' => 'Test'
        ],
        [
          'user_id' => 1,
          'user_name' => 'edlinkiii',
          'body' => 'Test'
        ],
        [
          'user_id' => 1,
          'user_name' => 'edlinkiii',
          'body' => 'Test'
        ],
        [
          'user_id' => 1,
          'user_name' => 'edlinkiii',
          'body' => 'Test'
        ],
      ]
    ];

    $this->view('pages/index', $data);
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
