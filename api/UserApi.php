<?php
namespace api;

use api\Api;
use models\User;
use Exception;
use libraries\Db;

class UserApi extends Api
{

	public function __construct()
	{
		parent::__construct();
		$this->run();
	}

	/**
	 * Список Api адресов
	 * 
	 */
	public function index_get()
	{
		# Если пользователь авторизован то покажем ему список точек входа для API
		if ($this->method == 'GET') {

			$apiUrl = [
				'index_get' => CONFIG['root_url'].'/user-api/index_get?token=',
				'user_get' => CONFIG['root_url'].'/user-api/user_get?id=100&token=',
				'auth_post' => CONFIG['root_url'].'/user-api/auth_post',
				'user-create_post' => CONFIG['root_url'].'/user-api/user-create_post',
				'user-update_post' => CONFIG['root_url'].'/user-api/user-update_post',
				'user_delete' => CONFIG['root_url'].'/user-api/user_delete?id=100'
			];

			print $this->response($apiUrl, 200);

		} else {
			print $this->response(['error' => $this->requestStatus(405)], 405);
		}

	}

	/**
	 * Получаем данные по пользователю в виде Json
	 * http://localhost/user-api/user_get?id=100&token=your_token
	 * @param int $id
	 */
	public function user_get(int $id)
	{

		if ($this->method == 'GET' && is_numeric($id)) {

			$mUser = User::getUser($id);

			if ($mUser) {
				print $this->response($mUser, 200);
			} else {
				print $this->response(['error' => $this->requestStatus(404)], 404);
			}

		} else {
			print $this->response(['error' => $this->requestStatus(405)], 405);
		}
		
	}

	/**
	 * Авторизация пользователя
	 * Этот метод создан для авторизации через браузер
	 * Вызываеться при обращении к контроллеру main/auth
	 * http://localhost/user-api/auth_post
	 */
	public function auth_post()
	{

		if ($this->method == 'POST') {

			$mUser = Db::pdoFindOne(User::tableName(), where: [
				'username' => $_POST['username'],
				'password' => md5($_POST['password'])
			]);

			if ($mUser) {
				print $this->response($mUser, 200);
			} else {
				print $this->response(['error' => $this->requestStatus(404)], 404);
			}

		} else {
			print $this->response(['error' => $this->requestStatus(405)], 405);
		}
		
	}

	/**
	 * Отправляем данные по новому пользователю в модель
	 * http://localhost/user-api/user-create_post
	 */
	public function userCreate_post()
	{

		if ($this->method == 'POST') {

			$mUser = User::save($_POST);

			if ($mUser) {
				print $this->response($mUser, 200);
			} else {
				print $this->response(['error' => $this->requestStatus(404)], 404);
			}

		} else {
			print $this->response(['error' => $this->requestStatus(405)], 405);
		}
		
	}

	/**
	 * Авторизация пользователя
	 * http://localhost/user-api/user-update_post?id=100
	 */
	public function userUpdate_post(int $id)
	{

		if ($this->method == 'POST') {

			if (User::getUser($id) && Db::pdoUpdate(User::tableName(), $id, $_POST)) {

				# Сразу запросим данные и выведим пользователя
				print $this->response(User::getUser($id), 200);

			} else {
				print $this->response(['error' => $this->requestStatus(404)], 404);
			}

		} else {
			print $this->response(['error' => $this->requestStatus(405)], 405);
		}
		
	}


	/**
	 * Удаляем пользователя
	 * http://localhost/user-api/user_delete?id=100
	 * Удаление пользователя
	 * @param int $id
	 */
	public function user_delete(int $id)
	{

		if ($this->method == 'DELETE') {

			if (is_numeric($id) && User::delete($id)) {

				print $this->response([
					$id => 'Пользователь удалён'
				], 200);

			} else {
				print $this->response(['error' => $this->requestStatus(404)], 404);
			}

		} else {
			print $this->response(['error' => $this->requestStatus(405)], 405);
		}

	}

} 