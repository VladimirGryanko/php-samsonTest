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