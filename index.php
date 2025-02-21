<?php
// ini_set("session.use_trans_sid", true);
// ini_set('session.use_cookies', 1);
// ini_set('session.auto_start', 1);
// ini_set('session.gc_maxlifetime', 3600*24*30);
// ini_set('session.cookie_lifetime', 3600*24*30);

session_start();

# Включаем вывод ошибок
error_reporting(E_ALL | E_STRICT);
ini_set('display_startup_errors', 0);
ini_set('display_errors','On');


if (version_compare(phpversion(), '8.0.0', '<') == true) { die('FROM PHP8.0 Only'); }

spl_autoload_register(function ($className) {
	$className = str_replace('\\', '/', $className);
	include $className.'.php';
});

# Поместим конфиги в константу CONFIG
define('CONFIG', include realpath('./config/common.php')); 

try {
    libraries\Db::getInstance();
} catch(PDOException $e){
    die($e->getMessage());
}

# Если есть данные для входа то логинимся и сохраняем это в сессию
define('IS_AUTH', models\User::login());

// setcookie('ip', $_SERVER['REMOTE_ADDR'], strtotime('+30 days'), '/', $this->config['host']);
// setcookie('user_id', 56, strtotime('+30 days'), '/', $this->config['host']);

// setcookie('ip', '', time()-360000, '/', $this->config['host']);
// setcookie('user_id', '', time()-360000, '/', $this->config['host']);
// print_r($_SESSION);
// print_r($_COOKIE);
// die;

$request_uri = CONFIG['request_uri'];

# Так как проверяем имена классов то начало будет в верхнем регистре
$nameController = (isset($request_uri['1'])) ? str_replace('-', '', ucwords($request_uri['1'], "-")) : 'Main';

# Так как проверяем методы, то начало будет в нижнем регистре
$nameAction = (isset($request_uri['2'])) ? str_replace('-', '', lcfirst(ucwords($request_uri['2'], "-"))) : 'index';

$fileController = 'controllers/'.$nameController.'Controller.php';
$fileControllerApi = 'api/'.$nameController.'.php';

if (file_exists($fileController)) {
	$instanceNameController = 'controllers\\'.$nameController.'Controller';
} else if (file_exists($fileControllerApi)) {
	$instanceNameController = 'api\\'.$nameController;
} else {
	header('Location: /');
}

$controller = new $instanceNameController;

# Проверяем есть ли такой метод в классе, если нету то такой страницы нету - 404
if (!in_array($nameAction, get_class_methods($controller))) {
	header('Location: /main/page404');
}

# Если в Get параметрах есть аргументы то передаём их в методы
$params = new ReflectionMethod($controller, $nameAction);

if ($params) {

	$args = [];

	foreach ($params->getParameters() as $param) {

		$val = @$_GET[$param->getName()];

		switch ($param->getType()) {
			case 'int':
				$val = (int)$val;
				break;
			case 'float':
				$val = (float)$val;
				break;
			case 'string':
				$val = (string)$val;
				break;
			case 'array':
				$val = (array)$val;
				break;
			case 'object':
				$val = (object)$val;
				break;
			case 'null':
				$val = null;
			default:
				$val = $val;
		}
		
		if (!empty($val) && $param->getType() == get_debug_type($val)) {
			$args[] = $val;
		} else if (!$param->isOptional()) {
			# Если аргумент не подходит или он пустой
			header( 'Location: /main/page404');
		}

	}

	$controller->$nameAction(...$args);

} else {
	$controller->$nameAction();
}
