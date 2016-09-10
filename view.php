<?php
  header("Content-Type: text/html; charset=utf-8");
  $humans = file_get_contents("humans0.json");
  $humans = json_decode($humans, true);
  print_r($humans);
?>
