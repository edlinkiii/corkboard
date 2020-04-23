<?php

class Favorites extends Controller {
  public function __construct() {
    $this->favoriteModel = $this->model('Favorite');
    $this->reactionModel = $this->model('Reaction');
    $this->postModel = $this->model('Post');
  }

  public function default() {
    return $this->show();
  }

  public function show() {
    $data = ['posts' => $this->postModel->favoritePosts()];
    
    $this->view('posts/show', $data);
  }

  public function list() {
    if(!isset($_SESSION['user_id'])) {
      die(json_encode(['Error' => 'Not logged in']));
    }
    $result = $this->favoriteModel->listFavorites();
    $output = [];
    foreach($result as $r) {
      $output[] = $r->post_id;
    }
    die(json_encode(['favoriteList' => implode(',',$output)]));
  }

  public function is($post_id) {
    if(!isset($_SESSION['user_id'])) {
      die(json_encode(['Error' => 'Not logged in']));
    }
    die(json_encode(['isFavorite' => $this->favoriteModel->isFavorite($post_id)]));
  }

  public function add($post_id) {
    if(!isset($_SESSION['user_id'])) {
      die(json_encode(['Error' => 'Not logged in']));
    }
    if($this->favoriteModel->isFavorite($post_id)) {
      die(json_encode(['result' => 1]));
    }
    die(json_encode(['result' => $this->favoriteModel->addFavorite($post_id)]));
  }

  public function remove($post_id) {
    if(!isset($_SESSION['user_id'])) {
      die(json_encode(['Error' => 'Not logged in']));
    }

    die(json_encode(['result' => $this->favoriteModel->removeFavorite($post_id)]));
  }
}
