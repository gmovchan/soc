<?php
  header("Content-Type: text/html; charset=utf-8");
  $handle = @fopen("city.txt", "r");
  $cities = array();
  if ($handle) {
      while (($buffer = fgets($handle)) !== false) {
        $buffer = str_replace("\n", "", $buffer);
//        $cities[] = $buffer;
        $cities[$buffer] = '';
      }
      if (!feof($handle)) {
          echo "Error: unexpected fgets() fail\n";
      }
      fclose($handle);
  }


  foreach ($cities as $key => $value) {
    if ($key == "Москва") {
      $cities[$key] = $arrayName = array('дс' => '',);
    } else if ($key == "Санкт-Петербург") {
      $cities[$key] = $arrayName = array('Питер' => '',
      'дс2' => '',
      'Петербург' => ''
      );
    } else if ($key == "Ростов-на-Дону") {
      $cities[$key] = $arrayName = array('рнд' => '',);
    }
  }

  print_r($cities);

  $cities = json_encode($cities);
  file_put_contents("cities.json", $cities);
?>
