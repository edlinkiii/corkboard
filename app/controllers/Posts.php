<?php

class Posts extends Controller {
  public function __construct() {
    $this->postModel = $this->model('Post');
    $this->stalkModel = $this->model('Stalk');
    $this->reactionModel = $this->model('Reaction');
  }

  // C -- create
  public function add() {
    if(!isset($_SESSION['user_id'])) {
      redirect('users/login');
    }
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

      $error = '';
      if($_POST['body'] == '') {
        $error = 'Error: Body text is required.';
      }

      if($error) {
        $data = [
          'title' => 'Add A Post',
          'form' => [
            'body' => $_POST['body'],
            'error' => $error
          ]
        ];
    
        $this->view('posts/add', $data);
      }
      else {
        $id = $this->postModel->addPost($_POST['body']);

        if($id) {
          redirect('posts/show/' . $id);
        }
      }
    }
    else {
      $data = [
        'title' => 'Add A Post',
        'form' => [
          'body' => '',
          'error' => ''
        ]
      ];
  
      $this->view('posts/add', $data);
    }
  }

  // R -- read -- get post(s)
  public function show($id = null) {
    if($id) {
      $data = $this->postModel->getPost($id);
      print_r($data);

      $this->view('posts/show', $data);
    }
    else {
      $data = ['posts' => $this->postModel->getPosts()];

      $this->view('posts/showAll', $data);
    }
  }

  public function stalk() {
    $data = ['posts' => $this->postModel->stalkPosts()];
    
    $this->view('posts/showAll', $data);
  }

  // U -- update
  public function edit($id = null) {
    if(!isset($_SESSION['user_id'])) {
      redirect('users/login');
    }
    // edit an existing post with id=$id
    $data = [
      'title' => 'Edit Your Post',
      'form' => [
        'body' => '',
        'id' => null,
        'error' => ''
      ]
    ];

    if($id) {
      $data['form']['id'] = $id;

      $row = $this->postModel->getPost($id);

      if($row->user_id != $_SESSION['user_id']) {
        redirect('pages/permission');
      }

      if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        $data['form']['body'] = $_POST['body'];

        // put if
        if($this->postModel->editPost($data['form'])) {
          redirect('posts/show/' . $id);
        }
      }
      else {
        $data['form']['body'] = $row->post_body;
  
        $this->view('posts/edit', $data);
      }
    }
    else {
        redirect('pages/permission');
    }
  }

  // D -- delete
  public function remove($id) {
    if(!isset($_SESSION['user_id'])) {
      redirect('users/login');
    }

    // delete an existing post
    $data = [
      'title' => 'Delete Your Post',
    ];

    if($id) {
      $data['id'] = $id;

      $row = $this->postModel->getPost($id);

      if($row->user_id != $_SESSION['user_id']) {
        redirect('pages/permission');
      }

      if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        // put if
        if($this->postModel->removePost($id)) {
          redirect('posts/show');
        }
      }
      else {
        $this->view('posts/remove', $data);
      }
    }
    else {
        redirect('pages/permission');
    }
  }

  public function react($post_id, $reaction_id) {
    // /posts/react/1/-1
  }
}
