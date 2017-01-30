<?php

namespace Core;

include_once 'DatabaseModelInterface.php';
include_once 'ProductParams.php';

abstract class DBCore implements Interfaces\DatabaseModelInterface {

    protected $link;

    public function __construct() {
        $this->link = $this->getDBConnection();
    }

    abstract public function insertToDB();

    abstract public function getById($id);
    
    /**
     * Заполняет свойства объекта значениями из БД
     */
    public function populateFields($result) {
        foreach ($result as $key => $value) {
            $this->__set($key, $value);
        }
    }

    /**
     * Подключаемся к бд для выполнения запросов
     * @return DB connect
     */
    private function getDBConnection() {
        $config = array('host' => 'localhost',
            'database' => 'test_phillips',
            'login' => 'root',
            'password' => '');
        $link = mysqli_connect($config['host'], $config['login'], $config['password']) or var_dump(mysqli_connect_error());
        mysqli_select_db($link, $config['database']) or var_dump("Could not select database");
        mysqli_query($link, "SET NAMES utf-8");
        mysqli_query($link, "SET CHARACTER SET utf-8");
        mysqli_query($link, "SET collation_connection = utf-8");
        mysqli_query($link, "SET character_set_results = utf-8");
        mysqli_query($link, "SET TIME_ZONE = '+03:00'");
        return $link;
    }

    /**
     * Выполнение запроса insert
     * @param string SQL insert query
     * @return integer ID of inserted string
     */
    public function insertQuery($query) {
        mysqli_query($this->link, $query) or die("Failed");
        return mysqli_insert_id($this->link);
    }

    /**
     * Возвращает результат запроса из бд
     * @param string SQL select query
     * @return array return FALSE, if row not finded in DB
     */
    public function getRowQuery($query) {
        $result = mysqli_query($this->link, $query) or die("Failed");
        if (mysqli_num_rows($result) > 0) {
            $this->populateFields(mysqli_fetch_assoc($result));
        } else {
            return FALSE;
        }
    }

}