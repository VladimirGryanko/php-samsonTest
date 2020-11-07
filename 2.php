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



function importXml($a)
{
  $code_category = 1;
  $link = mysqli_connect('localhost', 'root', 'root', 'test_samson');
  if (mysqli_connect_errno()) {
    echo mysqli_connect_errno();
  }
  $xml = simplexml_load_file($a);
  mysqli_query($link, "DELETE FROM category_product");
  mysqli_query($link, "DELETE FROM a_price");
  mysqli_query($link, "DELETE FROM a_property");
  mysqli_query($link, "DELETE FROM a_product ");
  mysqli_query($link, "DELETE FROM a_category ");
  mysqli_query($link, "ALTER TABLE `a_product` AUTO_INCREMENT = 1");
  foreach ($xml->Товар as $rows) {
    $attr = $rows->attributes();
    if (isset($attr['Код'])) {
      echo $code = $rows['Код'];
    } else {
      echo "В данном товаре нет кода </br>";
      continue;
    }
    if (isset($attr['Название'])) {
      echo $name = $rows['Название'];
    } else {
      echo "В данном товаре нет названия </br>";
      continue;
    }
    if (!isset($rows->Цена)) {
      echo "В данном товаре нет цены </br>";
      continue;
    }
    if (isset($rows->Свойства)) {
      foreach ($rows->Свойства as $prop) {
        if ($prop->count() == 0) {
          echo "В данном товаре нет ни одного свойства</br>";
          continue 2;
        }
      }
    } else {
      echo "В данном товаре нет свойств</br>";
      continue;
    }
    if (isset($rows->Разделы)) {
      if (!isset($rows->Разделы->Раздел)) {
        echo "В данном товаре нет ни одной категории </br>";
        continue;
      }
    } else {
      echo "В данном товаре нет категорий </br>";
      continue;
    }
    $sql = "INSERT INTO `a_product` (`id`, `code`, `name`) 
    VALUES (NULL, '" . 1 * $code . "','" . $name . "')";
    $res = mysqli_query($link, $sql);
    $res = mysqli_query($link, "SELECT id FROM a_product WHERE `code`= " . 1 * $code . "");
    $id = mysqli_fetch_assoc($res);
    $id = $id['id'];

    foreach ($rows->Цена as $column) {
      if ($column->attributes() != NULL) {
        $price_type =  $column->attributes();
      } else {
        $price_type = NULL;
      }
      echo $price_type;
      echo $price = $column;
      $sql = "INSERT INTO `a_price` (`id_product`, `price_type`, `price`) 
      VALUES ('" . $id . "', '" . $price_type . "','" . $price . "')";
      $res = mysqli_query($link, $sql);
    }

    foreach ($rows->Свойства as $row) {
      foreach ($row as $column) {

        if ($column->attributes() != NULL) {
          $property = $column->getName() . " " . $column->attributes()->getName() . " " . $column->attributes() . " ";
        } else {
          $property = $column->getName();
        }
        echo $property_v = $property . $column;
        $res = mysqli_query($link, "SELECT property_value FROM a_property WHERE `product`= " . $id . "");
        $property_value = mysqli_fetch_assoc($res);
        $property_value = $property_value['property_value'];
        $sql = "INSERT INTO `a_property` (`product`, `property_value`) VALUES ('" . $id . "','" . $property_v . "')";
        $res = mysqli_query($link, $sql);
      }
    }

    mysqli_query($link, "ALTER TABLE `a_category` AUTO_INCREMENT = 1");

    foreach ($rows->Разделы as $row) {
      foreach ($row as $column) {
        echo $category = $column;
        $sql = "INSERT INTO `a_category`(`id`, `code`, `name`) VALUES (NULL," . $code_category . ",'" . $category . "')";
        if (mysqli_query($link, $sql)) {
          $code_category++;
        }
        $res = mysqli_query($link, "SELECT id FROM a_category WHERE `name` = '" . $category[0] . "'");
        $id_category = mysqli_fetch_assoc($res);
        $id_category = $id_category['id'];
        $sql = "INSERT INTO `category_product` (`id_product`,`id_category`) VALUES (" . $id . "," . $id_category . ")";
        $res = mysqli_query($link, $sql);
      }
    }
    echo "</br>";
  }
}
importXml('data_samson.xml');

function exportXml($a, $b)
{
  $link = mysqli_connect('localhost', 'root', 'root', 'test_samson');
  if (mysqli_connect_errno()) {
    echo mysqli_connect_errno();
  }
  $dom = new DOMDocument();
  $dom->load($a);

  $sql = "SELECT id FROM `a_category` WHERE `code` =" . $b . "";
  $res = $link->query($sql);
  $id_category = mysqli_fetch_assoc($res);
  $id_category = $id_category['id'];
  $sql = "SELECT id_product FROM `category_product` WHERE `id_category` =" . $id_category . "";
  $res = $link->query($sql);
  while ($row = $res->fetch_assoc()) {
    $id_product[] = $row;
  }
  foreach ($id_product as $product) {
    $tovar = $dom->createElement('Товар');
    $sql = "SELECT code FROM a_product WHERE id = " . $product["id_product"] . "";
    $res = mysqli_query($link, $sql);
    $code = mysqli_fetch_assoc($res);
    $code = $code['code'];
    $sql = "SELECT `name` FROM a_product WHERE id = " . $product["id_product"] . "";
    $res = mysqli_query($link, $sql);
    $name = mysqli_fetch_assoc($res);
    $name = $name['name'];
    $tovar->setAttribute('Код', $code);
    $tovar->setAttribute('Название', $name);
    $sql = "SELECT price FROM a_price WHERE id_product = " . $product["id_product"] . "";
    $res = mysqli_query($link, $sql);
    $price = array();
    while ($row = $res->fetch_assoc()) {
      $price[] = $row;
    }
    foreach ($price as $price) {

      $sql = "SELECT price_type FROM a_price WHERE price = " . $price["price"] . "";
      $res = mysqli_query($link, $sql);
      $price_type = mysqli_fetch_assoc($res);
      $price_type = $price_type['price_type'];
      $p = $dom->createElement('Цена', $price["price"]);
      $p->setAttribute("Тип", $price_type);
      $tovar->appendChild($p);
    }
    $sql = "SELECT property_value FROM a_property WHERE product = " . $product["id_product"] . "";
    $result = mysqli_query($link, $sql);
    $property = array();
    while ($row = $result->fetch_assoc()) {
      $property[] = $row;
    }
    $prop = $dom->createElement('Свойства');
    $tovar->appendChild($prop);
    foreach ($property as $property) {
      $property_value = array();
      $property_value = explode(" ", $property["property_value"]);

      if ($property_value[1] != "") {
        $prop_v = $dom->createElement($property_value[0], $property_value[3]);
        $prop->appendChild($prop_v);
        $propAttr = $dom->createAttribute($property_value[1]);
        $propAttr->value = $property_value[2];
        $prop_v->appendChild($propAttr);
      } else {
        $prop_v = $dom->createElement($property_value[0], $property_value[3]);
        $prop->appendChild($prop_v);
      }
    }
    $sql = "SELECT id_category FROM category_product WHERE id_product = " . $product["id_product"] . "";
    $res = mysqli_query($link, $sql);
    $category = array();
    while ($row = $res->fetch_assoc()) {
      $category[] = $row;
    }
    $categ = $dom->createElement('Разделы');
    $tovar->appendChild($categ);
    foreach ($category as $category) {
      $sql = "SELECT `name` FROM a_category WHERE id = " . $category["id_category"] . "";
      $res = mysqli_query($link, $sql);
      $name_category = mysqli_fetch_assoc($res);
      $name_category = $name_category['name'];
      $category_value = $dom->createElement('Раздел', $name_category);
      $categ->appendChild($category_value);
    }
    $newTovar = $dom->importNode($tovar);
    $dom->appendChild($newTovar);
  }
  print_r($dom->save($a));
  mysqli_close($link);
}
// exportXml("data_samson.xml", 3);
