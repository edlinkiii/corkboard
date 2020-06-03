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
        $id = $this->postModel->addPost($_POST['body'], $_POST['img']);

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
        'img' => '',
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
        $data['form']['img'] = $_POST['img'];

        // put if
        if($this->postModel->editPost($data['form'])) {
          redirect('posts/show/' . $id);
        }
      }
      else {
        $data['form']['body'] = $row->post_body;
        $data['form']['img'] = $row->post_img;
  
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

  // upload pic, return location & filename
  public function pic() {
    $data = [
      'title' => 'Upload A Profile Pic',
      'form' => [
        'error' => '',
      ]
    ];

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

      $target_dir = DIRROOT . '/public/images/post_pic/';
      $target_file = $target_dir . basename($_FILES["file"]["name"]);
      $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
      $newFileName = md5($_SESSION['user_id']. '_' .time()) . '.' . $imageFileType;

      $check = getimagesize($_FILES["file"]["tmp_name"]);
      if($check !== false) {
      } else {
        $data['form']['error'] = 'Error: File is not an image';
      }

      // Check if file already exists
      if (file_exists($target_file)) {
        $data['form']['error'] = 'Error: File already exists';
      } 
      // Check file size
      if ($_FILES["file"]["size"] > (2 * 1024 * 1024)) {
        $data['form']['error'] = 'Error: File is too large';
      }
      // Allow certain file formats
      $allowedExtensions = ['.jpg', '.jpeg', '.png', '.gif'];
      if(in_array($imageFileType, $allowedExtensions)) {
        $data['form']['error'] = 'Error: File type is not allowed';
      } 

      // Check if $uploadOk is set to 0 by an error
      if (!$data['form']['error']) {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_dir . $newFileName)) {
          // save info in database
          // if($this->profileModel->updateProfilePic($newFileName)) {
          //   // set success message
          //   $_SESSION['message'] = 'Profile Pic Uploaded!';
          //   // redirect to profile
          //   redirect('users/profile');
          // }
          die($newFileName);
        }
        else {
          $data['form']['error'] = 'Error: There was an error uploading file';
        }
      }
    }

    // $this->view('settings/pic', $data);
    die($data['form']['error']);
  }
}
