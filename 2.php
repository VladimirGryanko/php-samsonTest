<?php
mb_internal_encoding("UTF-8");
$link = mysqli_connect('localhost', 'root', 'root', 'test_samson');
if (mysqli_connect_errno()) {
  echo mysqli_connect_errno();
}
function convertString($a, $b)
{
  if (substr_count($a, $b) >= 2) {
    $position1 = strpos($a, $b);
    $position2 = strpos($a, $b, $position1 + 1);
    $f = substr_replace($a, strrev($b), $position2, strlen($b));
    print_r($f);
    return $f;
  }
}
// Вывод функции convertString
// convertString('hello hello hello', 'el');
function mySortForKey($a, $b)
{
  foreach ($a as $key => $v) {
    if (!array_key_exists($b, $a[$key])) {
      throw new Exception("Во вложенном массиве с индексом  $key нет ключа '$b'");
      return 0;
    }
  }
  foreach ($a as $key => $row) {
    $volume[$key]  = $row[$b];
  }
  array_multisort($volume, SORT_ASC, $a);
  foreach ($a as $key => $v) {
    echo $key . "=";
    var_dump($v);
    echo "</br>";
  }
  echo "Отсортировано по ключу '$b' в порядке возрастания.</br>";
}
$array1 = array(
  ["a" => 5, "b" => 1],
  ["a" => 12, "b" => 8],
  ["a" => 1, "b" => 7],
  ["a" => 3, "b" => 10],
  ["a" => 1, "b" => 5]
);
//Массив входных данных
// Вывод функции mySortForKey
// try{
//   echo "1 сортировка : </br>";
//   echo mySortForKey($array1,"a");
//   echo "2 сортировка : </br>";
//   echo mySortForKey($array1,"c");
// }catch (Exception $e) {
//   echo "Ошибка: ".$e->getMessage();
// }
$xml = simplexml_load_file('data_samson.xml');
function importXml($a)
{
  $link = mysqli_connect('localhost', 'root', 'root', 'test_samson');
  if (mysqli_connect_errno()) {
    echo mysqli_connect_errno();
  }
  $code = 1;
  foreach ($a->Товар as $product) {
    $sql = "INSERT INTO `a_product` (`id`, `code`, `name`) 
    VALUES (NULL, '" . 1 * $product["Код"] . "','" . $product["Название"] . "')";
    $result = mysqli_query($link, $sql);
    $res = mysqli_query($link, "SELECT id FROM a_product WHERE `code`= " . 1 * $product["Код"] . "");
    $id = mysqli_fetch_assoc($res);
    $id = $id['id'];
    foreach ($product->Разделы as $categoryes) {
      foreach ($categoryes as $category) {
        $sql = "INSERT INTO `a_category`(`id`, `code`, `name`) VALUES (NULL," . $code . ",'" . $category . "')";
        if (mysqli_query($link, $sql)) {
          $code++;
        }
        $res = mysqli_query($link, "SELECT id FROM a_category WHERE `name` = '" . $category . "'");
        $id_category = mysqli_fetch_assoc($res);
        $id_category = $id_category['id'];
        $sql = "INSERT INTO `category_product` (`id_product`,`id_category`) VALUES (" . $id . "," . $id_category . ")";
        $result = mysqli_query($link, $sql);
      }
    }
    foreach ($product->Цена as $price) {
      $sql = "INSERT INTO `a_price` (`id_product`, `price_type`, `price`) 
      VALUES ('" . $id . "', '" . $price["Тип"] . "','" . $price . "')";
      $result = mysqli_query($link, $sql);
    }
    foreach ($product->Свойства as $property) {
      foreach ($property as $nameProperty) {
        $nm = $nameProperty->getName();
        $sql = "INSERT INTO `a_property` (`product`, `property_value`) VALUES ('" . $id . "', '" . $nm . " " . $nameProperty . " " . $nameProperty['ЕдИзм']  . "')";
        $result = mysqli_query($link, $sql);
      }
    }
  }
  mysqli_close($link);
}
importXml($xml);
function exportXml($a, $b)
{
  $link = mysqli_connect('localhost', 'root', 'root', 'test_samson');
  if (mysqli_connect_errno()) {
    echo mysqli_connect_errno();
  }
  $result = mysqli_query($link, "SELECT id FROM a_category WHERE `code` =" . $b . "");
  $id_category = mysqli_fetch_assoc($result);
  $id_category = $id_category['id'];
  $sql = "SELECT id_product FROM category_product WHERE id_category = " . $id_category . " ";
  $result = mysqli_query($link, $sql);
  while (($row = $result->fetch_array()) != false) {
    $id_product[] = $row;
  }
  foreach ($id_product as $product) {
    $sql = "SELECT code FROM a_product WHERE id = " . $product[0] . "";
    $result = mysqli_query($link, $sql);
    $code = mysqli_fetch_assoc($result);
    $code = $code['code'];
    $sql = "SELECT `name` FROM a_product WHERE id = " . $product[0] . "";
    $result = mysqli_query($link, $sql);
    $name = mysqli_fetch_assoc($result);
    $name = $name['name'];
    $tovar = $a->addChild('Товар',);
    $tovar->addAttribute('Код', $code);
    $tovar->addAttribute('Название', $name);
    $sql = "SELECT price FROM a_price WHERE id_product = " . $product[0] . "";
    $result = mysqli_query($link, $sql);
    $price = array();
    while (($row = $result->fetch_array()) != false) {
      $price[] = $row;
    }
    foreach ($price as $price) {
      $sql = "SELECT price_type FROM a_price WHERE price = " . $price[0] . "";
      $result = mysqli_query($link, $sql);
      $price_type = mysqli_fetch_assoc($result);
      $price_type = $price_type['price_type'];
      $priceproduct = $tovar->addChild('Цена', $price[0]);
      $priceproduct->addAttribute('Тип', $price_type);
    }
    $property_product = $tovar->addChild('Свойства');
    $sql = "SELECT property_value FROM a_property WHERE product = " . $product[0] . "";
    $result = mysqli_query($link, $sql);
    $property = array();
    while (($row = $result->fetch_array()) != false) {
      $property[] = $row;
    }
    foreach ($property as $property) {
      $property_value = array();
      $property_value = explode(" ", $property[0]);
      $parametr = $property_product->addChild($property_value[0], $property_value[1]);
      if ($property_value[2] != "") {
        $parametr->addAttribute('ЕдИзм', $property_value[2]);
      }
    }
    $category = $tovar->addChild('Разделы');
    $sql = "SELECT id_category FROM category_product WHERE id_product = " . $product['id_product'] . "";
    $result = mysqli_query($link, $sql);
    $categoryes = array();
    while (($row = $result->fetch_array()) != false) {
      $categoryes[] = $row;
    }
    foreach ($categoryes as $value) {
      $sql = "SELECT `name`FROM a_category WHERE id = " . $value[0] . " ";
      $result = mysqli_query($link, $sql);
      $nameCat = mysqli_fetch_assoc($result);
      $nameCat = $nameCat['name'];
      $category->addChild('Раздел', $nameCat);
    }
  }
  echo ($a->asXML('data_samson.xml'));
  mysqli_close($link);
}
exportXml($xml, 2);
