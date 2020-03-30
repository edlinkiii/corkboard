<?php

/**
 * Base controller
 * Loads models and views
 */
class Controller {
  // Load model
  public function model($model) {
    require_once '../app/models/' . $model . '.php';
    return new $model();
  }

  // Load view
  public function view($view, $data = []) {
    if(file_exists('../app/views/' . $view . '.php')) {
      require_once '../app/views/' . $view . '.php';
    }
    else {
      die('View does not exist');
    }
  }

  public function api($api, $data = []) {
    if(file_exists('../app/api/' . $api . '.php')) {
      require_once '../app/api/' . $api . '.php';
    }
    else {
      die('API does not exist');
    }
  }
}
