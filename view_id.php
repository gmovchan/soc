<?php
  $id = $_GET['id'];

  require_once('config.php');
  $mysqliList = new mysqli(
  $host,
  $user,
  $password,
  $db
  );
  if ($mysqliList->connect_errno) {
    echo "Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
  }

  if (!$mysqliList->set_charset("utf8")) {
    printf("Ошибка при загрузке набора символов utf8: %s\n", $mysqli->error);
  } else {
//    printf("Текущий набор символов: %s\n", $mysqli->character_set_name());
  }

  $queryList = "SELECT `id`, `from`, `description`, `target`, `contacts`, `timestamp`, `files` FROM `soc` WHERE `id`='$id'";

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Soc</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <br>
    <h2 class="text-center"><a href="/test/soc/view_id.php?id=
      <?php
      echo $id;
      ?>
      ">Анкета
    </a></h2>
    <br>
    <div class="container">

      <?php
        if ($resultList = $mysqliList->query($queryList)) {
          if ($resultList->num_rows) {

              while ($rowList = $resultList->fetch_assoc()) {

              $rowList['from'] = trim(substr($rowList['from'], 2));
              $rowList['description'] = trim(substr($rowList['description'], 2));
              $rowList['target'] = trim(substr($rowList['target'], 2));
              $rowList['contacts'] = trim(substr($rowList['contacts'], 2));

              echo '
                <div class="row">
                  <div class="col-xs-2 col-md-2">

                  </div>
                  <div class="col-xs-2 col-md-2">
                    <strong>Откуда:</strong>
                  </div>
                  <div class="col-xs-6  col-md-6">
                      '.$rowList['from'].'
                  </div>
                  <div class="col-xs-2  col-md-2">

                  </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-xs-2 col-md-2">

                  </div>
                  <div class="col-xs-2 col-md-2">
                    <strong>Описание:</strong>
                  </div>
                  <div class="col-xs-6  col-md-6">
                    '.$rowList['description'].'
                  </div>
                  <div class="col-xs-2  col-md-2">

                  </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-xs-2 col-md-2">

                  </div>
                  <div class="col-xs-2 col-md-2">
                    <strong>Кого ищет:</strong>
                  </div>
                  <div class="col-xs-6  col-md-6">
                    '.$rowList['target'].'
                  </div>
                  <div class="col-xs-2  col-md-2">

                  </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-xs-2 col-md-2">

                  </div>
                  <div class="col-xs-2 col-md-2">
                    <strong>Контакты:</strong>
                  </div>
                  <div class="col-xs-6  col-md-6">
                    '.$rowList['contacts'].'
                  </div>
                  <div class="col-xs-2  col-md-2">

                  </div>
                </div>
              ';

                $files = explode(",", $rowList['files']);

                echo '
                <br>
                <div class="row">
                  <div class="col-xs-2 col-md-2">

                  </div>
                <div class="col-xs-8  col-md-8">
                ';

                foreach ($files as $key => $value) {


                  $count = substr_count($value, '/');
                  for ($i=0; $i < $count; $i++) {
                    $num = stripos($value, '/');
                    $value = substr($value, $num + 1);
                  }

                  $path = "./img/".$value;

                  if ($value) {
                    echo '
                      <img src="'.$path.'" alt="" class="img-thumbnail ">
                    ';
                  }
                }

                echo '
                </div>
                <div class="row">
                  <div class="col-xs-2 col-md-2">

                  </div>
                ';

          }
        }
      }
        $resultList->free();
        $mysqliList->close();
      ?>
    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
