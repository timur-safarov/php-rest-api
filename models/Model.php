<?php

namespace models;

use libraries\Db;
use Exception;

abstract class Model extends Db
{
	
	abstract public static function tableName();

}