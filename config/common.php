<?php

return [
	'name' => 'Basic Auth Rest-APi',
	'host' => $_SERVER['HTTP_HOST'],
	'root_url' => 'http://'.$_SERVER['HTTP_HOST'],
	'request_uri' => array_diff(explode('/', strtok(getenv('REQUEST_URI'), '?')), ['']),
	'db' => [
		'host' => 'localhost',
		'dbname' => 'php-rest-api.local',
		'charset' => 'UTF8',
		'pass' => 'root',
		'login' => 'root'
	],
	# Данные для ADMIN пользователя для оьращения к API
	'rest' => [
		'username' => 'admin',
		'pass' => '12345',
		'email' => 'user@test.ru',
		'token' => 'test'
	]

];