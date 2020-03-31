<?php

class Users extends Controller {
  public function __construct() {
    $this->userModel = $this->model('User');
  }

  public function profile($user) {
    // if !$user, use current user from session
  }

  public function signup() {
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
      print_r($_POST);
      // die();

      $error = '';
      if($_POST['name'] == '') {
        $error = 'Error: A name is required.';
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
        $data = [
          'title' => 'Sign Up',
          'form' => [
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'password' => $_POST['password'],
            'confirm_password' => $_POST['confirm_password'],
            'error' => $error,
          ]
        ];
        $this->view('users/signup', $data);
      }
      else {
        // save user
        if($this->userModel->signup($_POST)) {
          $_SESSION['message'] = 'Account Created!';
          redirect('users/login');
        }
        // redirect
        // ??? display success message ???
      }
    }
    else {
      $data = [
        'title' => 'Sign Up',
        'form' => [
          'name' => '',
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
