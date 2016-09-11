<?php
  $city = $_POST['city'];
  $gender = $_POST['gender'];
  $targetGender = $_POST['targetGender'];

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

  if ($city) {
    $city = "`city`='".$city."'";
  } else {
    $city = "";
  }

  if ($city && $gender) {
    $gender = "AND `gender`='".$gender."'";
  } else if (!$city && $gender) {
    $gender = "`gender`='".$gender."'";
  } else{
    $gender = "";
  }

  if ($gender && $targetGender) {
    $targetGender = "AND `targetGender`='".$targetGender."'";
  } else if (!$city && !$gender && $targetGender) {
    $targetGender = "`targetGender`='".$targetGender."'";
  } else{
    $targetGender = "";
  }

  if (!$city && !$gender && !$targetGender) {
    $where = "";
  } else {
    $where = "WHERE";
  }

  $queryList = "SELECT `id`, `from`, `description`, `target`, `contacts`, `timestamp` FROM `soc` ".$where." ".$city." ".$gender." ".$targetGender." ORDER BY `timestamp` DESC";

  require_once("header.php");
//  echo $queryList;
  ?>
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

<?php
  require_once("footer.php");
?>
