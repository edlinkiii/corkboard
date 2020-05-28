<?php

class Users extends Controller {
  public function __construct() {
    $this->userModel = $this->model('User');
    $this->profileModel = $this->model('Profile');
    $this->postModel = $this->model('Post');
    $this->prefsModel = $this->model('Prefs');
    $this->stalkModel = $this->model('Stalk');
    $this->reactionModel = $this->model('Reaction');
    $_SESSION['active_link'] = '';
  }

  public function default($username=null) {
    return $this->profile($username);
  }

  public function profile($username = null) {
    $user_id = 0;
    if(!$username) {
      if($_SESSION['user_id']) {
        $user_id = $_SESSION['user_id'];
        $_SESSION['active_link'] = 'my_profile';
      }
      else {
        redirect('users/login');
      }
    }
    else {
      $user_id = $this->userModel->getUserID($username);
    }

    $_SESSION['more_method'] = "getPostsByUserId";
    $_SESSION['more_id'] = $user_id;
    $_SESSION['more_page'] = 0;

    $data = [
      'profile' => $this->profileModel->getProfileByUserId($user_id),
      'posts' => $this->postModel->getPostsByUserId($user_id),
      'prefs' => $this->prefsModel->getPrefs($user_id),
      'stalking' => $this->stalkModel->isStalking($user_id),
    ];

    $this->view('users/profile', $data);
  }

  public function signup() {
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

      $error = '';
      if($_POST['username'] == '') {
        $error = 'Error: A username is required.';
      }
      elseif($_POST['email'] == '') {
        $error = 'Error: An email is required.';
      }
      elseif($_POST['password'] == '') {
        $error = 'Error: A password is required.';
      }
      elseif(strlen($_POST['password']) < 6) {
        $error = 'Error: Password minimum length is 6.';
      }
      elseif($_POST['password'] != $_POST['confirm_password']) {
        $error = 'Error: Passwords must match.';
      }

      if($error != '') {
        if($this->userModel->usernameInUse($_POST['username'])) {
          $error = 'Error: Username is already in use.';
        }
        if($this->userModel->emailInUse($_POST['email'])) {
          $error = 'Error: Email is already in use.';
        }
      }

      if($error != '') {
        $data = [
          'title' => 'Sign Up',
          'form' => [
            'username' => $_POST['username'],
            'email' => $_POST['email'],
            'password' => $_POST['password'],
            'confirm_password' => $_POST['confirm_password'],
            'error' => $error,
          ]
        ];
        $this->view('users/signup', $data);
      }
      else {
        if($this->userModel->signup($_POST)) {
          $_SESSION['message'] = 'Account Created!';
          redirect('users/login');
        }
      }
    }
    else {
      $data = [
        'title' => 'Sign Up',
        'form' => [
          'username' => '',
          'email' => '',
          'password' => '',
          'confirm_password' => '',
          'error' => '',
        ]
      ];
      $this->view('users/signup', $data);
    }
  }

  public function login() {
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

      $loggedInUser = $this->userModel->login($_POST['username'], $_POST['password']);
      if($loggedInUser) {
        // establish session
        $this->createUserSession($loggedInUser);
        // redirect
        redirect('posts/show');
      }
      else {
        $data = [
          'title' => 'Log In',
          'form' => [
            'username' => $_POST['username'],
            'password' => '',
            'error' => 'Please check your username and password',
          ]
        ];
        $this->view('users/login', $data);
      }
    }
    else {
      $data = [
        'title' => 'Log In',
        'form' => [
          'username' => '',
          'password' => '',
          'error' => '',
        ]
      ];
      $this->view('users/login', $data);
    }
  }

  public function createUserSession($user) {
    $_SESSION['user_id'] = $user->id;
    $_SESSION['user_email'] = $user->email;
    $_SESSION['user_username'] = $user->username;
    $_SESSION['user_name'] = $user->name;
    $_SESSION['user_pic'] = $user->pic;
    redirect('posts');
  }

  public function logout() {
    unset($_SESSION['user_id']);
    unset($_SESSION['user_username']);
    unset($_SESSION['user_email']);
    unset($_SESSION['user_name']);
    session_destroy();
    redirect('users/login');
  }

  public function stalk($username) {
    $this->stalkModel->startStalking($this->userModel->getUserID($username));

    redirect('users/profile/' . $username);
  }

  public function unstalk($username) {
    $this->stalkModel->stopStalking($this->userModel->getUserID($username));

    redirect('users/profile/' . $username);
  }

  public function searchByName($name) {
    $data = [
      'users' => $this->userModel->searchUsersByName($name),
    ];

    die(json_encode($data));
  }

  public function check() {
    if(!isset($_SESSION['user_id'])) {
      $data = ['user_id' => 0];
    } else {
      $data = ['user_id' => $_SESSION['user_id']];
    }
    $json = json_encode($data);
    die($json);
  }
}
