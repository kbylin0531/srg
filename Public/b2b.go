<?php

// change the following paths if necessary
define('YII_PATH_BASE',dirname(__DIR__).'/');
$yii=YII_PATH_BASE.'Vendor/yii117/framework/yii.php';
$config=YII_PATH_BASE.'Web/Com/config/main.php';
include_once '../Sharin/Independence/litex.dev.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);
Yii::createWebApplication($config)->run();
