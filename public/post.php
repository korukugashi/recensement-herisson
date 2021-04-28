<?php

header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Headers: Content-Type, x-requested-with');
header('Content-Type: text/plain; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === "OPTIONS") {
  die();
}

try {
  $input = file_get_contents('php://input');
  $data = json_decode($input, true);

  if (!$data 
    || !isset($data['date']) || !isset($data['heure'])
    || !isset($data['lat']) || !isset($data['lon'])
    || !isset($data['paysage']) || !isset($data['typeObs'])
    || !isset($data['firstname']) || !isset($data['name']) || !isset($data['email'])
    || !isset($data['satisfaction']) || !isset($data['subscribe'])
    || !$data['date'] || !$data['heure']
    || !$data['lat'] || !$data['lon'] || empty($data['paysage']) || empty($data['typeObs'])
    || !$data['firstname'] || !$data['name'] || !$data['email']) {
    throw new \Exception('Missing parameters');
  }

  $filename = __DIR__.'/upload/data';
  file_put_contents($filename, $input."\n", FILE_APPEND);

  $c = 0;
  $fp = fopen($filename, 'r');
  if ($fp) {
    while (!feof($fp)) {
      $content = fgets($fp);
      if($content) $c++;
    }
  }
  fclose($fp);
  echo $c;
} catch (Exception $e) {
  header("HTTP/1.0 500 Internal Server Error");
  echo $e->getMessage();
}