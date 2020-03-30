<?php

class Users extends Controller {
  public function __construct() {
    $this->userModel = $this->model('User');
  }

  public function profile($user) {
    // if !$user, use current user from session
  }

  public function login() {
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

      $loggedInUser = $this->userModel->login($_POST['email'], $_POST['password']);
      if($loggedInUser) {
        // establish session
        $this->createUserSession($loggedInUser);
        // redirect
        redirect('/pages/index');
      }
      else {
        $data = [
          'title' => 'Log In',
          'form' => [
            'email' => $_POST['email'],
            'password' => '',
            'error' => 'Please check your email and password',
          ]
        ];
        $this->view('users/login', $data);
      }
    }
    else {
      $data = [
        'title' => 'Log In',
        'form' => [
          'email' => '',
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
    $_SESSION['user_name'] = $user->name;
    redirect('posts');
  }

  public function logout() {
    unset($_SESSION['user_id']);
    unset($_SESSION['user_email']);
    unset($_SESSION['user_name']);
    session_destroy();
    redirect('users/login');
  }
}
