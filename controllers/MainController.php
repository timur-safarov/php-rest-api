<?php
namespace controllers;

use Exception;
use models\User;
use libraries\Db;
use libraries\Curl;
use libraries\Template;

class MainController
{

	private $config;

	public function __construct() {
		$this->config = CONFIG;
		return __METHOD__;
	}

	/**
	 *  Вывод главной страницы с формой регистрации
	 *  @return string
	 */
	public function index()
	{

		if (IS_AUTH) {
			$apiUrl = json_decode((new Curl)->apiUrl(), true);
		}

		return Template::show('main/index', [
			'apiUrl' => isset($apiUrl) ? $apiUrl : false,
			'title' => (IS_AUTH) ? 'Вы авторизованы' : 'Форма регистрации'
		], 'main');
	}


	/**
	 *  Страница авторизации
	 *  @return string
	 */
	public function auth()
	{

		if (isset($_POST['user'])) {

			# Тут делаем запрос в rest Api чтобы получить данные по пользователю
			$responce = (new Curl)->auth($_POST['user']);

			# Если пользователь был получен - авторизовываем его
			if (isset($responce)) {
				User::auth($_POST['user']['username'], $_POST['user']['password']);
			}

			echo $responce;

		} else {

			return Template::show('main/auth', [
				'title' => (IS_AUTH) ? 'Вы авторизованы' : 'Форма авторизации'
			], 'main');

		}
	}

	/**
	 *  Добавление нового пользователя
	 *  @return string
	 */
	public function userCreate()
	{
		return (new Curl)->userCreate($_POST);
	}

	/**
	 *  Удаляем креденшелы и выкидываем пользователя
	 */
	public function logout()
	{
		setcookie('ip', '', time()-360000, '/', $this->config['host']);
		setcookie('user_id', '', time()-360000, '/', $this->config['host']);
		session_destroy();

		header('Location: /');
	}

	/**
	 *  Страница не найдена - 404
	 *  @return string
	 */
	public function page404()
	{
		return Template::show('main/404', [
			'title' => 'Страница не найдена - 404'
		], 'main');
	}
	

}