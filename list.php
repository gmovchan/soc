<?php
  $city = $_POST['city'];
  $gender = $_POST['gender'];
  $targetGender = $_POST['targetGender'];
/*
  if ($city == 'unknow') {
    $city = '';
  }
*/
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

  $queryList = "SELECT `id`, `from`, `description`, `target`, `contacts`, `timestamp` FROM `soc` WHERE `city`='$city' AND `gender`='$gender' AND `targetGender`='$targetGender' ORDER BY `timestamp` DESC";

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

    <div class="container">
      <div class="row">
        <div class="col-xs-4 col-md-4">

        </div>
        <div class="col-xs-4  col-md-4">
          <h2 class="text-center">Поиск по анкетам</h2>
          <?php
            require_once("form.php");
          ?>
        </div>
        <div class="col-xs-4  col-md-4">

        </div>
      </div>
      <?php
        if ($resultList = $mysqliList->query($queryList)) {
          if ($resultList->num_rows) {
              echo
              '<div class="row">
                <div class="col-xs-2  col-md-2">

                </div>
                <div class="col-xs-8  col-md-8">
                  <h2 class="text-center">Результат</h2>
                  <table class="table table-bordered">
                    <tr>
                      <td>
                        id
                      </td>
                      <td>
                        Откуда
                      </td>
                      <td>
                        Описание
                      </td>
                      <td>
                        Кого ищет
                      </td>
                      <td>
                        Контакты
                      </td>
                    </tr>';

              while ($rowList = $resultList->fetch_assoc()) {

              $rowList['from'] = trim(substr($rowList['from'], 2));
              $rowList['description'] = trim(substr($rowList['description'], 2));
              $rowList['target'] = trim(substr($rowList['target'], 2));
              $rowList['contacts'] = trim(substr($rowList['contacts'], 2));

              echo
                '<tr>
                  <td>
                    <a href="/test/soc/view_id.php?id='.$rowList['id'].'"> '.$rowList['id'].'</a>
                  </td>
                  <td>
                    '.$rowList['from'].'
                  </td>
                  <td>
                    '.$rowList['description'].'
                  </td>
                  <td>
                    '.$rowList['target'].'
                  </td>
                  <td>
                    '.$rowList['contacts'].'
                  </td>
                </tr>
              ';

              }
              echo
                  '</table>
                </div>
                <div class="col-xs-2  col-md-2">

                </div>
              </div>';

          } else {
            echo
              '<br>
              <div class="row">
                <div class="col-xs-4  col-md-4">

                </div>
                <div class="col-xs-4  bg-warning  col-md-4">
                  <h4 class="text-center">По вашему запросу ничего не найдено.</h4>
                </div>
                <div class="col-xs-4  col-md-4">

                </div>';
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
