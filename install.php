<?php
if (is_file('./data/install.lock')) {
    header('Location: ./index.php');
    exit;
}
define('FTX_DATA_PATH', './data/');
define('APP_NAME', 'install');
define('APP_PATH', './install/');
define('APP_DEBUG', true);
require("./thinkphp/setup.php");