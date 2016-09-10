<form class="" action="list.php" method="post">
  <div class="form-group">
    <label for="city">Город</label>
    <select class="form-control" name="city">
      <?php
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
          printf("Текущий набор символов: %s\n", $mysqli->character_set_name());
        }

        $query = "SELECT `city` AS `city`, count(*) AS `count` FROM `soc` GROUP BY `city`";

        if ($result = $mysqli->query($query)) {

          while ($row = $result->fetch_assoc()) {
            if ($row["city"] == '') {
              echo '<option value="unknow">Не определен ('.$row["count"].')</option>';
              continue;
            }
            echo '<option value="'.$row["city"].'">'.$row["city"].' ('.$row["count"].')</option>';
          }

          $result->free();
        }

        $mysqli->close();
      ?>
    </select>
    <label for="gender">Пол в анкете</label>
    <select class="form-control" name="gender">
      <option value="unknow">Неизвестно</option>
      <option value="male">Парень</option>
      <option value="female">Девушка</option>
    </select>
    <label for="targetGender">Кого ищет</label>
    <select class="form-control" name="targetGender">
      <option value="unknow">Неизвестно</option>
      <option value="male">Пареня</option>
      <option value="female">Девушку</option>
    </select>
  </div>
  <button type="submit" name="button" class="btn btn-primary">Показать</button>
</form>
