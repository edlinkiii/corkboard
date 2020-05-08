<?php

/**
 * App Core Class
 * Creates URL & loads core controller
 * URL FORMAT - /controller/method/params
 */
class Core {
  protected $currentController = 'Pages';
  protected $currentMethod = 'index';
  protected $params = [];

  public function __construct() {
    // print_r($this->getUrl());

    $url = $this->getUrl();

    // Look in controllers for first value
    if(isset($url[0]) && file_exists('../app/controllers/' . ucwords($url[0]) . '.php')) {
      $this->currentController = ucwords($url[0]);
      unset($url[0]);
    }

    require_once '../app/controllers/' . $this->currentController . '.php';
    $this->currentController = new $this->currentController;

    if(!isset($url[1])) { // '/u' --> '/u/default'
      $url[1] = 'default';
    }
    else if(is_numeric($url[1]) && !isset($url[2])) { // '/u/1' --> '/u/default/1'
      $num = $url[1];
      $url[2] = $num;
      $url[1] = 'default';
    }

    if(isset($url[1])) {
      if(method_exists($this->currentController, $url[1])) {
        $this->currentMethod = $url[1];
        unset($url[1]);
      } else {
        die('Error: The method `'.$url[1].'` does not exist.');
      }
    }

    $this->params = $url ? array_values($url) : [];

    call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
  }

  public function getUrl() {
    if(isset($_GET['url'])) {
      $url = rtrim($_GET['url'], '/');
      $url = filter_var($url);
      // $url = filter_var($url, FILTER_SANITIZE_URL);
      $url = explode('/', $url);
      return $url;
    }
  }
}
