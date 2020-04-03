<?php

class Settings extends Controller {
  public function __construct() {
    $this->userModel = $this->model('User');
    $this->profileModel = $this->model('Profile');
  }

  public function profile() {
    $profileData = $this->profileModel->getProfileByUserId($_SESSION['user_id']);
    
    $data = [
      'title' => 'Edit Your Profile',
      'form' => [
        'name' => $profileData->name,
        'birthdate' => $profileData->birthdate,
        'bio' => $profileData->bio,
        'location' => '',
        'error' => '',
      ]
    ];

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

      $data['form']['name'] = trim($_POST['name'] ? $_POST['name'] : $profileData->name);
      $data['form']['birthdate'] = trim($_POST['birthdate']);
      $data['form']['bio'] = trim($_POST['bio']);

      if(!trim($_POST['name'])) {
        $data['form']['error'] = 'A name is required';
      }
      else {
        if($this->profileModel->updateProfile($data['form'])) {
          redirect('users/profile');
        }
        else {
          $data['form']['error'] = 'Unexpected error updating the database';
        }
      }
    }

    $this->view('settings/profile', $data);
  }

  public function password() {
    $data = [
      'title' => 'Change Your Password',
      'form' => [
        'error' => '',
      ]
    ];

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

      $error = '';

      // check current password
      if($this->userModel->checkPassword($_POST['current_password'])) {
        if($_POST['desired_password'] == '') {
          $error = 'Error: Passwords cannot be blank.';
        }
        elseif($_POST['desired_password'] == $_POST['current_password']) {
          $error = 'Error: New password must be different.';
        }
        elseif(strlen($_POST['desired_password']) < 6) {
          $error = 'Error: Password minimum length is 6.';
        }
        elseif($_POST['desired_password'] != $_POST['confirm_password']) {
          $error = 'Error: Confirmation password must match.';
        }
      }
      else {
        $error = 'Error: Current password is incorrect.';
      }

      if($error == '') {
        if($this->userModel->changePassword($_POST['desired_password'])) {
          $_SESSION['message'] = 'Password Changed!';
          redirect('users/profile');
        }
        else {
          $error = 'Error: Unexpected database error.';
        }
      }

      $data['form']['error'] = $error;
    }
    $this->view('settings/password', $data);
  }

  public function prefs() {
    $this->view('settings/prefs');
  }

  public function pic() {
    $this->view('settings/pic');
  }
}
