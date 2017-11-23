<?php
# session
session_start();
# constantes de l'application
define('SALT', 'pU1TIYoa6f3Gmqkg0UviAewPvkCLc9mCxKJsVFUX2cU9CiasvsLei');
# database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_DBNAME', 'sheep');
define('PAGINATE', 20);

# autoloader
require __DIR__ . '/library/helpers.php';
require __DIR__ . '/controllers/front_controller.php';
require __DIR__ . '/controllers/back_controller.php';
require __DIR__ . '/model/spend_model.php';
require __DIR__ . '/model/addSpend_model.php';
# request
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];