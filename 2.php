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
