<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
define('SCRIPT_URL',    $_SERVER['SCRIPT_NAME']);
define('BASE_URL',      dirname($_SERVER['SCRIPT_NAME']));
define('PATH_BASE',     dirname(__DIR__).'/');
define('PATH_PUBLIC',   dirname($_SERVER['SCRIPT_FILENAME']).'/');//脚本所在目录即公共目录
//数据目录
define('PATH_DATA',     PATH_BASE.'data/');//关键数据
define('PATH_RUNTIME',  PATH_BASE.'runtime/');//运行时临时数据
define('PATH_COOKIE',   PATH_RUNTIME.'cookie/');
define('PATH_DOWNLOAD', PATH_RUNTIME.'download/');

define('IS_HTTPS',   isset ($_SERVER ['HTTPS']) and $_SERVER ['HTTPS'] === 'on');
define('HTTP_PREFIX',IS_HTTPS ? 'https://' : 'http://' );
$script_dir = rtrim(dirname($_SERVER['SCRIPT_NAME']),'/');
define('PUBLIC_URL',HTTP_PREFIX.$_SERVER['SERVER_NAME'].((80 == $_SERVER['SERVER_PORT'])?
        $script_dir :
        ":{$_SERVER['SERVER_PORT']}{$script_dir}"));
define('NOW',$_SERVER['REQUEST_TIME']);

const CATE_CACHE = true;//获取分类时是否使用缓存
const SIMILAR_SCALA = 70;//行业名称相似度 70%

/**
 * 分类配置
 * [
 *  0   => '实现类的名称',
 *  1   => '平台地址',
 *  2   => '存在的问题描述，文本被设置时或者值为true时将禁止一部分的缓存行为，不包括分类缓存',
 * ]
 */
const CATE_CONF = [
    0   => [
        'BossgooCategory',
        'bossgoo.com',
    ],
    1   => [
        'DiyTradeCategory',
        'diytrade.com',
    ],
    2   => [
        'WtexpoCategory',
        'wtexpo.com'
    ],
    3   => [
        'WjwCategory',
        'wjw.com',
        ''
    ],
    4   => [
        'WeikuCategory',
        'weiku.com',
        ''
    ],
    5   => [
        'EcvvCategory','ecvv.com'
    ],
    6   => [
        'WdtradeCategory','wdtrade.com'
    ],
    7   => [
        'TradettCategory','tradett.com','登录无法实现',
    ],
    8   => [
        'TtnetCategory','ttnet.net',true
    ],
    9   => [
        'Ec21Category','ec21.com',
    ],
];

//spl_autoload_register(function ($clsnm){
//    static $_map = [];
//    if(false !== strpos(ltrim($clsnm,'\\'),'Library\\')){
//        //类名称以Library开头的都认为可能属于该类库
//        $path = PUBE_BASE_DIR.str_replace('\\', '/', $clsnm).'.class.php';
//        if(is_readable($path)) include_once $_map[$clsnm] = $path;
//    }
//},true,true);
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'My Web Application',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
        'application.components.B2BCategory.*',//b2b行业分类获取
        'application.components.B2BMember.*',
        'application.components.B2BProduct.*',
        'application.components.InformationProvider.*',
    ),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		/*
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'Enter Your Password Here',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		*/
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		// uncomment the following to enable URLs in path-format
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
                BASE_URL.'/<controller:\w+>/<id:\d+>'=>'<controller>/view',
                BASE_URL.'/<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
                BASE_URL.'/<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		'sqlite'=>array(
			'connectionString' => 'sqlite:'.PATH_DATA.'pub.db',
		),
		// uncomment the following to use a MySQL database
		'db'=>array(
            'class'=>'system.db.CDbConnection',
            'connectionString' => 'mysql:host=192.168.99.99;dbname=bossgoo',
            'username' => 'bossgoo',
            'password' => 'bossgoo',
            'charset' => 'utf8',
            'tablePrefix' => 'nt_',
            'emulatePrepare' => true,
            'enableProfiling' => true,
            'schemaCachingDuration' => 3600,
		),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
	),
);