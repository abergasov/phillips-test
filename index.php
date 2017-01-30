<?php

require_once (__DIR__.'/src/Models.php');
use Core\Models;

//создаем объект Customer, указываем имя и помещаем в бд
$customer = new Models\Customer();
$customer->__set('name', 'Alejandro');
$customer->insertToDB();

$inputStr = '{
 "id": 1,
 "title": "Товар номер 1",
 "params": {
   "country": "Russia",
   "price": 1030,
   "attributes": {
     "Вес": 1,
     "Объем": 0.2,
     "Цвет": "Красный"
   }
 }
}
';

$json  = json_decode($inputStr, TRUE);

//создаем объект Product и заполняем данными
$product = new Models\Product();
foreach ($json as $key => $value) {
    $product->__set($key, $value);
}
echo 'Исходный массив: ';
print_r($product->__get('params'));
echo '<hr>Значение ключа Вес: ';
var_dump($product->getParam('Вес'));
echo '<hr>Изменение значение поля Вес: ';
$product->setParam('Вес', 666);
var_dump($product->__get('params'));
echo '<hr>Изменение значение поля Вес: ';
var_dump($product->unsetParam('Вес'));
echo('<hr>Удаление поля вес: ');
var_dump($product->__get('params'));
$product->insertToDB();


$order = new Models\Order();
$order->__set('params', $json['params']);
$id = $order->insertToDB();
$order->getById($id);