<?php

namespace models;

use Exception;
use PDOException;

class User extends Model
{

	private static $user = null;

	/**
	 * Метод для вывода имени таблицы
	 * @return string
	 */
	public static function tableName()
	{
		// разделяем строку по символу "\" и берём последнее значение
		$explode = explode('\\', __CLASS__);
		return strtolower(end($explode));
	}


	/**
	 * Получение данные пользователя по id
	 * @param int $id
	 */
	public static function getUser(int $id)
	{
		return parent::pdoSelectAssoc(self::tableName(), $id);
	}

	/**
	 * Получение данные пользователя по полю
	 * 
	 */
	public static function findOne(array $where, array $fields=[])
	{
		return parent::pdoFindOne(self::tableName(), $where, $fields);
	}

	/**
	 * Метод сохранения нового пользователя
	 * @param array $fields
	 * @return boolean
	 */
	public static function save(array $fields)
	{
		$userData = [
			'username' => $fields['username'],
			'email' => $fields['email'],
			'password' => md5($fields['password']),
			'token' => sha1(random_int(time() - 10000000, time())) // 40 символов
		];

		$res = parent::pdoSave(self::tableName(), $userData, $userData);

		if (!$res) {
			return false;
		}

		# Вернём пользователя
		return self::getUser(parent::lastInsertId());

	}

	/**
	 * Удаление пользователя по id
	 * @param int $id
	 */
	public static function delete(int $id)
	{
		return parent::pdoDelete(self::tableName(), $id);
	}


	/**
	 * Авторизация пользователя
	 * 
	 */
	public static function auth(string $username, string $password)
	{

		$mUser = parent::pdoFindOne(self::tableName(), where: [
			'username' => $username,
			'password' => md5($password)
		]);

		# Устанавливаем сессии для авторизованого
		# Устанавливаем куку для того чтобы авторизация сохранилась на какой то срок
		if ($mUser) {

			$_SESSION['user']['username'] = $username;
			$_SESSION['user']['password'] = md5($password);

			# На 1 месяц
			# Сохраняем в куку IP пользователя чтобы проверять что она принадлежит этому пользователю
			setcookie('ip', $_SERVER['REMOTE_ADDR'], strtotime('+30 days'), '/', $_SERVER['HTTP_HOST']);
			setcookie('user_id', $mUser['id'], strtotime('+30 days'), '/', $_SERVER['HTTP_HOST']);

			// print_r($_SESSION);
			// print_r($_COOKIE);
			// print_r($mUser);

			return $mUser;

		} else {
			return false;
		}

	}

	/**
	 * Получаем данные для авторизованного пользователя
	 * 
	 * @return array
	 */
	public static function getAuthUser()
	{

		# Чтобы больше метод не запускать сохраним текущего пользователя
		if (self::$user === null) {

			$mUser = null;

			if (!empty($_SESSION['user']['username']) && !empty($_SESSION['user']['password'])) {

				# Пароль в сессиях у нас уже храниться в md5 и мы можем запросить пользователя
				$mUser = parent::pdoFindOne(tableName: self::tableName(), where: [
					'username' => $_SESSION['user']['username'],
					'password' => $_SESSION['user']['password']
				]);
		
			} else if (
				(isset($_COOKIE['user_id']) && is_numeric($_COOKIE['user_id'])) &&
				(isset($_COOKIE['ip']) && $_COOKIE['ip'] == $_SERVER['REMOTE_ADDR'])
			) {
				$mUser = User::getUser($_COOKIE['user_id']);
			}

			self::$user = $mUser;

		}

		return self::$user;
	}


	/**
	 * Авторизовываемся на основе сессий
	 * Создаём константу, которая просто указывает на то что пользователь авторизован
	 * 
	 * @return boolean
	 */
	public static function login()
	{
		return self::getAuthUser() ? true : false;
	}

}