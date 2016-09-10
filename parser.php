<?php
  header("Content-Type: text/html; charset=utf-8");
  $soc = file_get_contents("https://2ch.hk/soc/res/3161305.json");
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

  print_r($post[1]);

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

  print_r($human);

  $human = json_encode($human);
  file_put_contents("humans1.json", $human);

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

  function genderParse($info, $gender)
  {
    foreach ($gender as $key => $value) {
      $pos = mb_stripos($info, $key, 0, 'UTF-8');
      if ($pos) {
        return $value;
      }
    }
  }
?>
