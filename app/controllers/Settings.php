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
    $data = [
      'title' => 'Upload A Profile Pic',
      'form' => [
        'error' => '',
      ]
    ];

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

      if (!file_exists(DIRROOT . '/uploads/'. $_SESSION['user_id'])) {
        mkdir(DIRROOT . '/uploads/'. $_SESSION['user_id'], 0777, true);
      }
      if (!file_exists(DIRROOT . '/uploads/'. $_SESSION['user_id'] .'/profile_pic/')) {
        mkdir(DIRROOT . '/uploads/'. $_SESSION['user_id'] .'/profile_pic/', 0777, true);
      }

      $target_dir = DIRROOT . '/uploads/'. $_SESSION['user_id'] .'/profile_pic/';
      $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
      $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
      $newFileName = time() . '.' . $imageFileType;

      $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
      if($check !== false) {
      } else {
        $data['form']['error'] = 'Error: File is not an image';
      }

      // Check if file already exists
      if (file_exists($target_file)) {
        $data['form']['error'] = 'Error: File already exists';
      } 
      // Check file size
      if ($_FILES["fileToUpload"]["size"] > 5_000_000) {
        $data['form']['error'] = 'Error: File is too large';
      }
      // Allow certain file formats
      $allowedExtensions = ['.jpg', '.jpeg', '.png', '.gif'];
      if(in_array($imageFileType, $allowedExtensions)) {
        $data['form']['error'] = 'Error: File type is not allowed';
      } 

      // Check if $uploadOk is set to 0 by an error
      if (!$data['form']['error']) {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_dir . $newFileName)) {
          // save info in database
          $this->profileModel->updateProfilePic($newFileName);
          // set success message
          $_SESSION['message'] = 'Profile Pic Uploaded!';
          // redirect to profile
          redirect('users/profile');
        } else {
          $data['form']['error'] = 'Error: There was an error uploading file';
        }
      }
    }

    $this->view('settings/pic', $data);
  }
}

/*
Array
(
    [fileToUpload] => Array
        (
            [name] => remember_its_monday.gif
            [type] => image/gif
            [tmp_name] => C:\xampp\tmp\php3CD2.tmp
            [error] => 0
            [size] => 3848277
        )
)
*/
