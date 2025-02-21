<?php

namespace libraries;


/**
 * Класс для Curl запросов
 * 
 */
Class Curl {

    private $config;

	public function __construct() {
		$this->config = CONFIG;
	}

    public function apiUrl()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            # Так как получение данные то авторизовываемся просто по токену
            CURLOPT_URL => $this->config['root_url'].'/user-api/index_get?token='.$this->config['rest']['token'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_POSTFIELDS => [],
            CURLOPT_HTTPHEADER => array(
                'accept: application/json',
                'cache-control: no-cache',
                'X-HTTP-METHOD: GET' //Указываем наш метод GET|POST|PUT|DELETE
            ),
        ));

        $err = curl_error($curl);

        if ($err) {
            $responce = false;
        } else {
            $responce = curl_exec($curl);
        }

        curl_close($curl);

        return $responce;
    }

    /**
     * Тут делаем запрос в rest Api чтобы получить данные по пользователю
     * 
     */
    public function getUser(int $id)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            # Так как получение данные то авторизовываемся просто по токену
            CURLOPT_URL => $this->config['root_url'].'/user-api/user_get?token='.$this->config['rest']['token'].'&id='.$id,
            CURLOPT_RETURNTRANSFER => false,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_POSTFIELDS => [],
            CURLOPT_HTTPHEADER => array(
                'accept: application/json',
                'cache-control: no-cache',
                'X-HTTP-METHOD: GET' //Указываем наш метод GET|POST|PUT|DELETE
            ),
        ));

        $err = curl_error($curl);

        if ($err) {
            $responce = false;
        } else {
            $responce = curl_exec($curl);
        }

        curl_close($curl);

        return $responce;
    }

    /**
     * Тут делаем запрос в rest Api чтобы получить данные по пользователю
     * 
     */
    public function auth(array $user)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->config['root_url'].'/user-api/auth_post?token='.$this->config['rest']['token'],
            CURLOPT_HEADER => false,
            CURLOPT_FRESH_CONNECT => false,
            CURLOPT_NOBODY => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $user,
            # если CURLOPT_RETURNTRANSFER = true тогда echo curl_exec($curl)
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                # 'Content-Type: application/json',
                # Если другой заголовок то POST будет пустой
                'Content-Type: multipart/form-data',
                # Указываем авторизационные данные для API
                'Authorization: Basic ' . base64_encode(
                                            $user['username'] . 
                                            ':' . 
                                            $user['password']
                                        ),
                'cache-control: no-cache',
                'X-HTTP-METHOD: POST' //Указываем наш метод GET|POST|PUT|DELETE
            ),
        ));

        $err = curl_error($curl);

        if ($err) {
            $responce = false;
        } else {
            $responce = curl_exec($curl);
        }

        curl_close($curl);

        return $responce;
    }


    /**
     * Тут делаем запрос в rest Api чтобы добавить пользователя
     * @param array $user
     */
    public function userCreate(array $user)
    {
		$curl = curl_init();

		curl_setopt_array($curl, array(
            CURLOPT_URL => $this->config['root_url'].'/user-api/user-create_post?token='.$this->config['rest']['token'],
            CURLOPT_HEADER => false,
            CURLOPT_FRESH_CONNECT => FALSE,
            CURLOPT_NOBODY => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $user,
            # если CURLOPT_RETURNTRANSFER = true тогда echo curl_exec($curl)
            CURLOPT_RETURNTRANSFER => false,

            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                # 'Content-Type: application/json',
                # Если другой заголовок то POST будет пустой
                'Content-Type: multipart/form-data',
                # Указываем авторизационные данные для API base64_encode("user:password")
                'Authorization: Basic ' . base64_encode(
                                            $this->config['rest']['username'] . 
                                            ':' . 
                                            $this->config['rest']['pass']
                                        ),
                'cache-control: no-cache',
                'X-HTTP-METHOD: POST' //Указываем наш метод GET|POST|PUT|DELETE
            )
		));

		$err = curl_error($curl);

		if ($err) {
		  $responce = false;
		} else {
		  $responce = curl_exec($curl);
		}

		curl_close($curl);

		return $responce;

    }


}