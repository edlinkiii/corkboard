<?php

require_once DIRROOT . '/app/controllers/Users.php';

class U extends Users {
  public function __construct() {
    parent::__construct();
  }

  public function default($id=null) {
    return $this->profile($id);
  }
}
