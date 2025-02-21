<?php
namespace api;

use libraries\Db;
use models\User;
use Exception;

abstract class Api
{

    /**
     * GET|POST|PUT|DELETE
     */
    protected $method = '';
    public $requestUri = [];
    public $requestParams = [];

    /**
     * Название метод для выполнения
     */
    protected $action = '';


    public function __construct() {
        header('Access-Control-Allow-Orgin: *');
        header('Access-Control-Allow-Methods: *');
        header('Content-Type: application/json');

        //Массив GET параметров разделенных слешем
        $this->requestUri = CONFIG['request_uri'];
        $this->requestParams = $_REQUEST;

        try {

            //Определение метода запроса
            if (array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)) {

                if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'POST') {
                    $this->method = 'POST';
                } else if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'GET') {
                    $this->method = 'GET';
                } else if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'DELETE') {
                    $this->method = 'DELETE';
                } else if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'PUT') {
                    $this->method = 'PUT';
                } else {
                    throw new Exception("Unexpected Header");
                }

            } else {
                $this->method = $_SERVER['REQUEST_METHOD'];
            }

        } catch(Exception $e) {
            die($this->response(['error' => $e->getMessage()]));
        }
    }

    /**
     * Проверяем можно ли подключиться к API
     */
    public function run() {

        try {

            # Если обновление или добавление или удаление
            if (in_array($this->method, ['POST', 'DELETE', 'PUT'])) {

                $credentials = base64_decode(str_ireplace('Basic ', '', $_SERVER['HTTP_AUTHORIZATION']));
                $credentials = explode(':', $credentials);

                if (count($credentials) < 2) {
                    throw new Exception('User Not Found', 404);
                }
                
                $user = $credentials[0];
                $pass = $credentials[1];

                if ($user != CONFIG['rest']['username']
                    || $pass != CONFIG['rest']['pass']) {

                    $mUser = User::findOne([
                        'username' => $user,
                        'password' => md5($pass)
                    ]);

                    # Если пользователя нету ни в базе, ни в конфигах
                    # Тогда выбрасываем пользователя
                    if (!$mUser) {
                        throw new Exception('User Not Found', 404);
                    }
                }
            }

            if (in_array($this->method, ['GET'])) {

                # Если просто Get то проверяем чтобы был токен
                if(@$_GET['token'] != CONFIG['rest']['token']) {

                    # Ищем пользователя с таким токеном
                    $mUser = User::findOne([
                        'token' => @$_GET['token']
                    ]);

                    if (!$mUser) {

                        $credentials = base64_decode(str_ireplace('Basic ', '', $_SERVER['HTTP_AUTHORIZATION']));
                        $credentials = explode(':', $credentials);

                        if (count($credentials) < 2) {
                            throw new Exception('API token is invalid', 404);
                        }
                        
                        $user = $credentials[0];
                        $pass = $credentials[1];

                        if ($user != CONFIG['rest']['username'] ||
                            $pass != CONFIG['rest']['pass']
                        ) {

                            # Ищем пользователя с таким же именем если и его нету то выбрасываем ошибку
                            $mUser = User::findOne([
                                'username' => $user,
                                'password' => md5($pass)
                            ]);

                            if (!$mUser) {
                                throw new Exception('API token is invalid', 404);
                            }
                        }

                    }
                        
                }

            }

        } catch(Exception $e) {
            
            header('WWW-Authenticate: Basic realm="' . CONFIG['name'] . '"');
            header('HTTP/1.0 401 Unauthorized');

            die($this->response(['error' => $e->getMessage()], $e->getCode()));
        }
    }

    protected function response($data, $status = 500) {
        header("HTTP/1.1 " . $status . " " . $this->requestStatus($status));

        # Добавим в вывод код ошибки в базе если есть
        if (is_numeric(Db::getInstance()->errorCode()) && Db::getInstance()->errorCode() > 0) {
            $data['sql-code'] = Db::getInstance()->errorCode();
        }

        # Добавим в вывод информацию об ошибке в базе
        if (isset(Db::getInstance()->errorInfo()[2])) {
            $data['sql-info'] = Db::getInstance()->errorInfo()[2];
        }

        return json_encode($data);
    }

    public function requestStatus($code) {
        $status = array(
            200 => 'OK',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
        );
        return ($status[$code])?$status[$code]:$status[500];
    }

    abstract protected function index_get();
    abstract protected function userCreate_post();
    abstract protected function user_get(int $id);
    abstract protected function auth_post();
    abstract protected function user_delete(int $id);
    abstract protected function userUpdate_post(int $id);
}
