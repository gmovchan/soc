<?php
  $str = "http://2ch.hk/soc/thumb/3123453/14726536880550s.jpg";

  $count = substr_count($str, '/');
  for ($i=0; $i < $count; $i++) {
    $num = stripos($str, '/');
    $str = substr($str, $num + 1);
  }
  $str1 = "2ch";
  $elem1 = stripos($str1, '2');
  $elem2 = stripos($str1, '/');
  print_r(stripos($str1, '/'));

  echo $elem1 == false;
  echo "<br>";
  echo gettype($elem2);
  echo "<br>";
  echo !($elem2 === 0);
  echo "<br>";
  echo $str;
  echo "<br>";
  echo $elem2 == '';
  echo "<br>";
  echo '0' == '';
  echo "<br>";
  echo $num;
?>
