<?php

// DB PARAMS
define('DB_HOST', 'localhost');
define('DB_USER', 'cork');
define('DB_PASS', '!P@ssw0rd1234');
define('DB_NAME', 'cork');

// CONSTANTS
define('DIRROOT', dirname(dirname(dirname(__FILE__))));
define('APPROOT', dirname(dirname(__FILE__)));
define('URLROOT', 'http://localhost:8888/corkboard');
define('URLBASE', '/corkboard');
define('SITENAME', 'C0rkē');

define('DATE_FORMAT', 'F j, Y');
define('DATETIME_FORMAT', 'F j, Y @ g:ia');

define('NOTIFICATION_TYPE__REACTION', 1);
define('NOTIFICATION_TYPE__REPLY', 2);
define('NOTIFICATION_TYPE__FAVORITE', 3);
define('NOTIFICATION_TYPE__MENTION', 4);
