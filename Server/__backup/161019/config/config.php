<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
define('URL', 'http://localhost/');


/* Server Info Setting */
define('SERVER_NAME', gethostname());
// define('SERVER_IP', gethostbyname(SERVER_NAME));
define('SERVER_IP', '121.169.219.99');


/* Gov. Data Service Key */
define('SERVICE_KEY', 'NoDqsCghIdBYA7NqkU2%2F0z1Eb%2BPUwsGB2vnDoDYLHKx4ugPH4H70ROU%2FwUp%2FFqrcBjTtAbBOxeROTUUGYhN2AA%3D%3D');


/* DB Setting */
define('DB_TYPE', 'mysql');
define('DB_HOST', 'localhost');
define('DB_NAME', 'roptop');
define('DB_USER', 'root');
define('DB_PASS', '6264');


/* Context Setting */
define('TITLE', 'SmartHome IoT Service');
define('PATH_PLUGIN', URL . 'webapp/resources/plugins/');
define('PATH_IMG', URL . 'webapp/resources/img/');
define('PATH_CSS', URL . 'webapp/resources/css/');
define('PATH_JS', URL . 'webapp/resources/js/');
