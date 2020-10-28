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