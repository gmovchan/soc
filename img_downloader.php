<?php
  header("Content-Type: text/html; charset=utf-8");
  require_once('config.php');
  $mysqli = new mysqli(
  $host,
  $user,
  $password,
  $db
  );
  if ($mysqli->connect_errno) {
    echo "Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
  }

  if (!$mysqli->set_charset("utf8")) {
    printf("Ошибка при загрузке набора символов utf8: %s\n", $mysqli->error);
  }

  imgDownload();

  function imgDownload() {
    global $mysqli;
    $countRecord = 0;
    $countExist = 0;
    $countNotExist = 0;
    $query = "SELECT `files` FROM `soc` WHERE `files`!=''";

    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc()) {
          $files = explode(",", $row['files']);
          foreach ($files as $key => $value) {
            $url = 'http://2ch.hk/soc/'.$value;

            $count = substr_count($value, '/');
            for ($i=0; $i < $count; $i++) {
              $num = stripos($value, '/');
              $value = substr($value, $num + 1);
            }

//            echo "<br>".$url;
            $path = "./img/".$value;
//            echo "<br>".$value;
            if (!file_exists($path)) {
              if (fopen($url, "r")) {
                $countRecord++;
                echo "<br>Файл существует и записан";

                $img = file_get_contents($url);
                file_put_contents($path, $img);
              } else {
                $countNotExist++;
                echo "<br>Файл не найден";
              }
            } else {
              $countExist++;
              echo "<br>Файл уже существует на сервере";
            }
          }
        }
        echo "<br>Картинок записано ".$countRecord;
        echo "<br>Картинок не найдено ".$countNotExist;
        echo "<br>Картинок обработано ".($countRecord + $countExist + $countNotExist);
    }
  }

?>
