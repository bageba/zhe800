<?php
if (!is_file('./data/install.lock')) {
    header('Location: ./install.php');
    exit;
}

define('FTX_VERSION', '5.0');
define('FTX_RELEASE', '20131210');
define('APP_NAME', 'app');
define('APP_PATH', './app/');
define('FTX_DATA_PATH', './data/');
define('EXTEND_PATH',	APP_PATH . 'Extend/');
define('CONF_PATH',		FTX_DATA_PATH . 'config/');
define('RUNTIME_PATH',	FTX_DATA_PATH . 'runtime/');
define('HTML_PATH',		FTX_DATA_PATH . 'html/');

define('APP_DEBUG', false);
require("./thinkphp/setup.php");