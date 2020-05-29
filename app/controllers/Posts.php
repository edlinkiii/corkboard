<?php

class Posts extends Controller {
  public function __construct() {
    $this->postModel = $this->model('Post');
    $this->stalkModel = $this->model('Stalk');
    $this->reactionModel = $this->model('Reaction');
    $this->notificationModel = $this->model('Notification');
    $_SESSION['active_link'] = '';
  }

  public function default() {
    return $this->show(null);
  }

  // C -- create
  public function add() {
    if(!isset($_SESSION['user_id'])) {
      redirect('users/login');
    }
    $_SESSION['active_link'] = 'add_post';
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
      if(!$_POST['body']) {
        die('test');
      }
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
          { // notification of tagged users
            preg_match_all('/\/u\/\d+\)/', $_POST['body'], $matches);
  
            for($i=0; $i < count($matches[0]); $i++) {
              $this->notificationModel->addNotification(substr($matches[0][$i], 3, -1), $id, NOTIFICATION_TYPE__MENTION);
            }
          }
  
          $_SESSION['active_link'] = '';
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

  public function reply($post_id) {
    if(!isset($_SESSION['user_id'])) {
      $error = ['Error' => 'You are not logged in.'];
      $json = json_encode($error);
      die($json);
    }

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
      $input = json_decode(file_get_contents('php://input'), true);

      if($input['body'] == '') {
        $error = ['Error' => 'Body text is required.'];
        $json = json_encode($error);
        die($json);
      }

      $data = [
        'post_id' => $post_id,
        'user_id' => $_SESSION['user_id'],
        'reply_count' => $this->postModel->addReply($input['body'], $post_id)->total,
        'notification_id' => $this->notificationModel->addNotification($this->postModel->getPostOwner($post_id), $post_id, NOTIFICATION_TYPE__REPLY),
      ];

      { // notification of tagged users
        preg_match_all('/\/u\/\d+\)/', $input['body'], $matches);

        for($i=0; $i < count($matches[0]); $i++) {
          $this->notificationModel->addNotification(substr($matches[0][$i], 3, -1), $post_id, NOTIFICATION_TYPE__MENTION);
        }
      }

      $json = json_encode($data);

      die($json);
    }
  }

  // R -- read -- get post(s)
  public function more() {
    $_SESSION['more_page']++;
    $method = $_SESSION['more_method'];
    $id = $_SESSION['more_id'];
    $data = [
      'posts' => $this->postModel->$method($id)
    ];

    $json = json_encode($data);
    die($json);
  }
  public function show($id = null) {
    if(!$id) {
      $_SESSION['active_link'] = 'all';
    }

    $_SESSION['more_method'] = ($id) ? "getReplies" : "getPosts";
    $_SESSION['more_id'] = $id;
    $_SESSION['more_page'] = 0;

    $data = [
      'posts' => ($id) ? $this->postModel->getPost($id) : $this->postModel->getPosts(),
      'replies' => ($id) ? $this->postModel->getReplies($id) : null,
    ];

    $this->view('posts/show', $data);
  }

  public function stalk() {
    if(!isset($_SESSION['user_id'])) {
      redirect('users/login');
    }

    $_SESSION['active_link'] = 'stalking';

    $_SESSION['more_method'] = "stalkPosts";
    $_SESSION['more_id'] = null;
    $_SESSION['more_page'] = 0;

    $data = ['posts' => $this->postModel->stalkPosts()];
    
    $this->view('posts/show', $data);
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

      $row = $this->postModel->getPost($id)[0];

      if($row->user_id != $_SESSION['user_id']) {
        redirect('pages/permission');
      }

      if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        { // notification of tagged users
          preg_match_all('/\/u\/\d+\)/', $_POST['body'], $matches);

          for($i=0; $i < count($matches[0]); $i++) {
            $this->notificationModel->addNotification(substr($matches[0][$i], 3, -1), $id, NOTIFICATION_TYPE__MENTION);
          }
        }

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

      $row = $this->postModel->getPost($id)[0];

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

  public function react($post_id, $reaction_id = false) {
    // /posts/react/1/-1
    $data = [
      'post_id' => $post_id,
      'user_id' => $_SESSION['user_id'],
      'total' => $this->reactionModel->setReaction($post_id, $reaction_id)->total,
      'reaction_id' => $reaction_id,
      'notification_id' => $this->notificationModel->addNotification($this->postModel->getPostOwner($post_id), $post_id, NOTIFICATION_TYPE__REACTION),
    ];
    $json = json_encode($data);
    die($json);
  }
}
