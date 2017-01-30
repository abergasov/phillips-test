<?php
//для теста - vendor\bin\phpunit unit\TestOrder.php
require_once dirname(dirname(__FILE__)) . '\vendor\autoload.php';
require_once (dirname(dirname(__FILE__)) . '\src\Models.php');

use Core\Models;

class TestOrder extends PHPUnit_Framework_TestCase {

    /**
     * Проверка работы со свойствами
     */
    function testCorrectPopulateFields() {
        for ($i = 0; $i < 10; $i++) {
            $field = $this->generateParams();

            $model = new \Core\Models\Order();
            $model->__set('params', $field['params']);
            //проверка что значение корректно утсановилось
            $this->assertSame($field['params'], $model->__get('params'));
            $id = $model->insertToDB();
            $value = $model->getById($id);
            //проверка на корректность значения после сохранения/извлечения из бд
            $this->assertSame($field['params'], $value['params']);
            
            //проверка работы с полем params - поиск/изменение/удаление
            foreach ($field['keys'] as $key => $value) {
                //проверка корректности поиска
                $this->assertSame($value, $model->getParam($key));
                
                //проверка что корректно изменяются значения
                $newValue = $this->randStr();
                $result = $model->setParam($key, $newValue);
                $this->assertSame($result, $newValue);
                
                //провека что значение удалено корректно
                $model->unsetParam($key);
                $this->assertFalse($model->getParam($key));
            }
        }
    }

    function generateParams () {
        $num = rand (1, 10);
        //сгенерированный массив
        $temp = array();
        //ключи для проверки
        $keys = array();
        
        for ($i = 0; $i <= $num; $i++) {
            $key = $this->randStr();
            $val = $this->randStr();
            $temp[$key] = $val;
            //если генератор вернет больше 10, добавляем ключи для проверки
            if (rand(10, 20) > 10) {
               $keys[$key] = $val; 
            } else {//иначе генерим вложенный массив
                $subArray = $this->generateParams();
                $temp[$key] = $subArray['params'];
                array_merge($keys, $subArray['keys']);
            }
        }
        return array('params'=>$temp, 'keys'=>$keys);
    }
    
    /**
     * генерирует случайное число
     * @return type случайная строка
     */
    function randStr() {
        return md5(time() . mt_rand(1, 1000000));
    }

}
