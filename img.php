<?php

  $url = 'http://2ch.hk/soc/src/3123453/14711813462070.jpg';
  $path = "./img/14711813462070.jpg";
  $img = file_get_contents($url);
  //echo $img;
  file_put_contents($path, $img);

/*
  $ch = curl_init('http://2ch.hk/soc/src/3123453/14711813462070.jpg');
  $fp = fopen('./img/14711813462070.jpg', 'wb');
  curl_setopt($ch, CURLOPT_FILE, $fp);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_exec($ch);
  curl_close($ch);
  fclose($fp);
  */
?>
