<?php
  header("Content-Type: text/html; charset=utf-8");

  function parse2ch($link) {

    $soc = file_get_contents($link);
    $soc = json_decode($soc, true);

    $cities = file_get_contents("cities.json");
    $cities = json_decode($cities, true);

    $gender = array(
      'кун' => 'male',
      'юноша' => 'male',
      'М ' => 'male',
      ' М,' => 'male',
      'парень' => 'male',
      'парня' => 'male',
      'кунчика' => 'male',
      'тян' => 'female',
      'девочк' => 'female',
      'девушк' => 'female',
      'тню' => 'female',
      'Ж ' => 'female',
      ' Ж,' => 'female'
    );

    $post = $soc["threads"][0]["posts"];

    $human = array();

    foreach ($post as $key => $value) {
      $comment = explode("<br>", $value["comment"]);
      $form = array();

      foreach ($comment as $key => $valueComment) {
        $valueComment = htmlspecialchars_decode(strip_tags($valueComment));
        $twoSymbols = substr($valueComment, 0, 2);
        if ($twoSymbols == "1 " || $twoSymbols == "1." || $twoSymbols == "1)") {
          $form["from"] = $valueComment;
          $form["city"] = cityParse($valueComment, $cities);
          $form["gender"] = genderParse($valueComment, $gender);
        } else if ($twoSymbols == "2 " || $twoSymbols == "2." || $twoSymbols == "2)") {
          $form["description"] = $valueComment;

        } else if ($twoSymbols == "3 " || $twoSymbols == "3." || $twoSymbols == "3)") {
          $form["target"] = $valueComment;
          $form["targetGender"] = genderParse($valueComment, $gender);
        } else if ($twoSymbols == "4 " || $twoSymbols == "4." || $twoSymbols == "4)") {
          $form["contacts"] = $valueComment;
        }

      }

      if ($form) {

        $form["timestamp"] = $value["timestamp"];
        $form["id"] = $value["num"];

        if (is_array($value["files"])) {
          foreach ($value["files"] as $key => $valueValue) {
              $form["files"][$key]["path"] = $valueValue["path"];
              $form["files"][$key]["thumbnail"] = $valueValue["thumbnail"];
          }
        }
        $human[] = $form;
      }
    }
//    print_r($human);
    return $human;
  }

  function genderParse($info, $gender) {
    foreach ($gender as $key => $value) {
      $pos = mb_stripos($info, $key, 0, 'UTF-8');
      if ($pos) {
        return $value;
      }
    }
  }

  function cityParse($info, $cities)
  {
    foreach ($cities as $key => $value) {
      if (is_array($cities[$key])) {
        foreach ($cities[$key] as $key1 => $value1) {
          $pos = mb_stripos($info, $key1, 0, 'UTF-8');
          if ($pos) {
            return $key;
          }
        }
      }
      $pos = mb_stripos($info, $key, 0, 'UTF-8');
      if ($pos) {
        return $key;
      }
    }
  }

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
  } else {
  }

  $humans = parse2ch("https://2ch.hk/soc/res/3161305.json");

  inserSQL($humans, $db);

  function inserSQL ($file, $db) {
    $count = 0;
    $countInsert = 0;
    global $mysqli;
    foreach ($file as $key => $value) {
      $from = $value['from'];
      $city = $value['city'];
      $gender = $value['gender'];
      $description = $value['description'];
      $target = $value['target'];
      $targetGender = $value['targetGender'];
      $contacts = $value['contacts'];
      $timestamp = $value['timestamp'];
      $id_soc = $value['id'];
      $images = '';

      $queryId = "SELECT * FROM `soc` WHERE `id_soc`='$id_soc'";


      if ($resultId = $mysqli->query($queryId)) {
        if (!$resultId->num_rows) {
          if (is_array($value['files'])) {
            $images = array();
            foreach ($value['files'] as $key1 => $value1) {
              $images[] = $value1[thumbnail];
            }
            $images = implode(",", $images);
          }

          $queryInsert =   "INSERT INTO `$db`.`soc` (`from`, `city`, `gender`,
          `description`, `target`, `targetGender`, `contacts`, `timestamp`, `id_soc`, `files`)
          VALUES ('$from', '$city', '$gender', '$description', '$target', '$targetGender', '$contacts',
          '$timestamp', '$id_soc', '$images')";

          if (!$mysqli->query($queryInsert)) {
            echo "Не удалось создать таблицу: (" . $mysqli->errno . ") " . $mysqli->error;
          }
          $countInsert++;
        } else {
          $count++;
        }
      }
    }
    echo "Всего записей обработано ".($countInsert + $count)."<br>";
    echo "Всего записей добавлено ".$countInsert."<br>";
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
//                echo "<br>Файл существует и записан";

                $img = file_get_contents($url);
                file_put_contents($path, $img);
              } else {
                $countNotExist++;
//                echo "<br>Файл не найден";
              }
            } else {
              $countExist++;
//              echo "<br>Файл уже существует на сервере";
            }
          }
        }
        echo "<br>Картинок записано ".$countRecord."<br>";
        echo "Картинок не найдено ".$countNotExist."<br>";
        echo "Картинок обработано ".($countRecord + $countExist + $countNotExist)."<br>";
    }
  }

  $mysqli->close();

?>
