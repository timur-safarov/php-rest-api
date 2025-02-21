<?php

namespace libraries;

use PDO;
use Exception;
use PDOException;

/**
 * Класс для методов DataBase
 * 
 */
Class Db {

	private static $instance = null;
    private function __construct(){}

    /**
     * Предотвращает клонирование экземпляра класса
     */
    final function __clone(){}

    /**
	 * Метод для подключения к Базе
	 * @param array $config
	 * @return resource
	 */
    public static function getInstance()
    {
        
        if (self::$instance === null) {

        	$db = CONFIG['db'];

            self::$instance = new PDO("mysql:host=$db[host];dbname=$db[dbname];charset=$db[charset]", $db['login'], $db['pass'], array(
				PDO::ATTR_PERSISTENT => true
			));
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
        }
        
        return self::$instance;
    }


	/**
	 * Метод для добавления записей в Базу
	 * @param string $table
	 * @param array $values
	 * @param array $source
	 * @return bool|int
	 */
	public static function pdoSave(string $tableName, array &$values, array $source = [])
    {

        $set = "INSERT INTO {$tableName} (";
        $concat = [];

        $allowed = $values;
        $values = [];
        if (!$source) $source = &$_POST;

        foreach ($allowed as $k => $val) {
            if (isset($source[$k])) {
                $concat[] = "`$k`";
                $values[$k] = "'$source[$k]'";
            }
        }
        
        return self::getInstance()->exec($set.implode(',', $concat).") VALUES(". implode(',', $values).")");

	}

	/**
     * Метод для множественного добавления записей в Базу
     * @param string $table
     * @param array $values
     * @param array $source
     * @return bool|int
     */
    public static function pdoSaveBunch(string $tableName, array &$values, array $source = [])
    {

        $set = "INSERT INTO {$tableName} (";
        $concat = [];

        $allowed = $values;
        $values = [];
        if (!$source) $source = &$_POST;

        foreach ($allowed as $k => $val) {

            //Проверяем есть ли массив для добавления сразу множества записей
            if (is_array($val)) {

                foreach ($val as $k2 => $val2) {

                    if (isset($source[$k])) {
                        $concat[$k2] = "`$k2`";
                        $values[$k][$k2] = "'$val2'";
                    }

                }

                $values[$k] = "(" . implode(",", $values[$k]) . ")";
            }

        }

        return self::getInstance()->exec($set.implode(',', $concat).") VALUES". join(",", $values));

    }

    /**
	 * Метод для удаления записи
	 */
    public static function pdoDelete(string $tableName, int $id)
    {
        $query = self::getInstance()->exec(
            'DELETE FROM ' . $tableName . ' WHERE id="' . $id . '"'
        );

        # показываем результат  
        return $query;
    }

    /**
	 * Метод для извлечения данных в виде обекта
	 */
	public static function pdoSelectObj(string $tableName, array &$fields, int $id)
    {

        $query = self::getInstance()->query(
            'SELECT ' . ($fields ? implode(',', $fields) : ' * ') .
            ' FROM ' . $tableName .
            ' WHERE id="' . $id . '"'
        );

        # Устанавливаем режим извлечения данных  
        $data = $query->fetch(PDO::FETCH_OBJ);
        
        # показываем результат  
        return $data;
    
	}

    /**
	 * Метод для извлечения данных в виде массива по id
	 */
	public static function pdoSelectAssoc(string $tableName, int $id, array $fields=[])
    {

        $query = self::getInstance()->query(
            'SELECT ' . ($fields ? implode(',', $fields) : ' * ') .
            ' FROM ' . $tableName .
            ' WHERE id="' . $id . '"'
        );

        # Устанавливаем режим извлечения данных  
        $data = $query->fetch(PDO::FETCH_ASSOC);
        
        # показываем результат  
        return $data;
            
	}

    /**
	 * Метод для извлечения данных в виде массива по полю
	 */
	public static function pdoFindOne(string $tableName, array $where, array $fields=[])
    {

        $fields = $fields ? implode(',', $fields) : ' * ';

        $where = array_map(function($k, $v){
            return "$k='$v'";
        }, array_keys($where), array_values($where));

        $where =  $where ? ' WHERE ' . implode(' AND ', $where) : '';
        
        $query = self::getInstance()->query(
            "SELECT $fields FROM " . $tableName . $where
        );

        # Устанавливаем режим извлечения данных  
        $data = $query->fetch(PDO::FETCH_ASSOC);
        
        # показываем результат  
        return $data;

	}

    /**
	 * Метод для извлечения данных пользователей по их полям
	 */
	public static function pdoFindAll(string $tableName, array $where=[], $fields = [])
    {

        $fields = $fields ? implode(',', $fields) : ' * ';

        $where = array_map(function($k, $v){
            return "$k='$v'";
        }, array_keys($where), array_values($where));

        $where =  $where ? ' WHERE ' . implode(' AND ', $where) : '';

        $query = self::getInstance()->query(
            "SELECT $fields FROM " . $tableName . $where
        );

        # Устанавливаем режим извлечения данных  
        $data = $query->fetch(PDO::FETCH_ASSOC);

        # показываем результат  
        return $data;
        
	}

    /**
     * Вытаскиваем одну запись - запрос в виде SQL
     * 
     */
    public static function query(string $sql)
    {

        $query = self::getInstance()->query($sql);

        # Устанавливаем режим извлечения данных  
        $data = $query->fetch(PDO::FETCH_ASSOC);

        # показываем результат  
        return $data;

    }
    
	/**
	 * Метод для обновления записей в Базе
	 */
	public static function pdoUpdate(string $tableName, int $id, array $fields)
    {

        $fields = array_map(function($k, $v){
            return "$k='$v'";
        }, array_keys($fields), array_values($fields));

        $query = self::getInstance()->query(
            'UPDATE ' . $tableName . ' SET ' . implode(', ', $fields) . ' WHERE id="' . $id . '"'
        );

        # показываем результат  
        return $query;
	}

    /**
     * Возвращает id последнего insert запроса
     * @return int
     */
    public static function lastInsertId()
    {
        $query = self::getInstance()->query("SELECT LAST_INSERT_ID()");
        $lastId = $query->fetchColumn();

        return $lastId;
    }



}