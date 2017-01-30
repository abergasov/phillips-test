<?php
namespace Core\Models;

include_once 'DBEngine.php';
include_once 'ProductParams.php';

use Core\DBCore;
use Core\Traits\ProductParams;

class Customer extends DBCore {

    private $id;
    private $name;
    private $created_at;

    public function __construct() {
        parent::__construct();
    }

    public function insertToDB() {
        $query = "INSERT INTO customers SET name = '" . addslashes($this->name) . "'";
        return $this->insertQuery($query);
    }

    public function getById($id) {
        if ((int) $id == 0) {
            return FALSE;
        }
        $query = "SELECT * FROM customers WHERE id = '" . (int) $id . "'";
        $result = $this->getRowQuery($query);
    }

    public function __get($property) {
        switch ($property) {
            case 'id':
                return $this->id;
            case 'name':
                return $this->name;
            case 'created_at':
                return $this->created_at;
        }
    }

    public function __set($property, $value) {
        switch ($property) {
            case 'name':
                $this->name = $value;
                break;
            case 'id':
                $this->id = (int)$value;
                break;
            case 'created_at':
                $this->created_at = $value;
                break;
        }
    }

}

class Product extends DBCore {

    private $id;
    private $title;
    private $created_at;
    private $params;
    
    use ProductParams;

    public function __construct() {
        parent::__construct();
    }

    public function insertToDB() {
        $this->beforeSave();
        $query = "REPLACE INTO products SET id = '" . $this->id . "', title = '" . addslashes($this->title) . "', "
                . "params = '" . addslashes($this->params) . "'";
        return $this->insertQuery($query);
    }

    public function getById($id) {
        if ((int) $id == 0) {
            return FALSE;
        }
        $query = "SELECT * FROM products WHERE id = '" . (int) $id . "'";
        $result = $this->getRowQuery($query);
        //$result['params'] = $this->toObject($result['params']);
       // return $result;
    }
    
    public function __get($property) {
        switch ($property) {
            case 'id':
                return $this->id;
            case 'title':
                return $this->title;
            case 'params':
                return $this->params;
            case 'created_at':
                return $this->created_at;
        }
    }

    public function __set($property, $value) {
        switch ($property) {
            case 'id':
                $this->id = (int) $value;
                break;
            case 'title':
                $this->title = $value;
                break;
            case 'params':
                $this->setParams($value, FALSE);
                break;
            case 'created_at':
                $this->created_at = $value;
                break;
        }
    }

}

class Order extends DBCore {

    private $id;
    private $created_at;
    private $params;
    
    use ProductParams;

    public function __construct() {
        parent::__construct();
    }

    public function insertToDB() {
        $this->beforeSave();
        $query = "INSERT INTO orders SET params = '" . addslashes($this->params) . "'";
        return $this->insertQuery($query);
    }

    public function getById($id) {
        if ((int) $id == 0) {
            return FALSE;
        }
        $query = "SELECT * FROM orders WHERE id = '" . (int) $id . "'";
        $result = $this->getRowQuery($query);
        //$result['params'] = $this->toObject($result['params']);
       // return $result;
    }
    
    public function __get($property) {
        switch ($property) {
            case 'id':
                return $this->id;
            case 'params':
                return $this->params;
            case 'created_at':
                return $this->created_at;
        }
    }

    public function __set($property, $value) {
        switch ($property) {
            case 'params':
                $this->setParams($value, FALSE);
                break;
            case 'id':
                $this->id = (int)$value;
                break;
            case 'created_at':
                $this->created_at = $value;
                break;
        }
    }

}
