<?php
  header("Content-Type: text/html; charset=utf-8");

  $humans = file_get_contents("humans1.json");
  $humans = json_decode($humans, true);

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

  inserSQL($humans);

  function inserSQL ($file) {
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
      $queryInsert =   "INSERT INTO `$db`.`soc` (`from`, `city`, `gender`,
      `description`, `target`, `targetGender`, `contacts`, `timestamp`, `id_soc`, `files`)
      VALUES ('$from', '$city', '$gender', '$description', '$target', '$targetGender', '$contacts',
      '$timestamp', '$id_soc', '$images')";

      if ($resultId = $mysqli->query($queryId)) {
        if (!$resultId->num_rows) {
          if (is_array($value['files'])) {
            $images = array();
            foreach ($value['files'] as $key1 => $value1) {
              $images[] = $value1[thumbnail];
            }
            $images = implode(",", $images);
          }

          if (!$mysqli->query($queryInsert)) {
            echo "Не удалось создать таблицу: (" . $mysqli->errno . ") " . $mysqli->error;
          }
          echo "<br>Анкета добавлена в БД.";
        } else {
          echo "<br>Анкета уже есть в БД.";
        }
      }
    }
  }

  $mysqli->close();
?>
