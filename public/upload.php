<?php

header('Access-Control-Allow-Origin: http://localhost:3000');
header('Content-Type: text/plain; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === "OPTIONS") {
  die();
}

try {
  if (!isset($_FILES['file']['error']) || is_array($_FILES['file']['error']) || !isset($_POST['id'])) {
    throw new RuntimeException('Invalid parameters.');
  }

  switch ($_FILES['file']['error']) {
    case UPLOAD_ERR_OK:
      break;
    case UPLOAD_ERR_NO_FILE:
      throw new RuntimeException('No file sent.');
    case UPLOAD_ERR_INI_SIZE:
    case UPLOAD_ERR_FORM_SIZE:
      throw new RuntimeException('Exceeded filesize limit.');
    default:
      throw new RuntimeException('Unknown errors.');
  }

  if ($_FILES['file']['size'] > 2000000) {
    throw new RuntimeException('Exceeded filesize limit.');
  }

  $finfo = new finfo(FILEINFO_MIME_TYPE);
  if (false === $ext = array_search(
    $finfo->file($_FILES['file']['tmp_name']),
    array(
      'jpg' => 'image/jpeg',
    ),
    true
  )) {
    throw new RuntimeException('Invalid file format.');
  }

  if (!move_uploaded_file(
    $_FILES['file']['tmp_name'],
    sprintf(
      __DIR__.'/upload/%u-%s-%u-%u.%s',
      $_POST['id'],
      sha1_file($_FILES['file']['tmp_name']),
      isset($_POST['pubPhoto']) && $_POST['pubPhoto'] ? 1 : 0,
      isset($_POST['pubNom']) && $_POST['pubNom'] ? 1 : 0,
      $ext
    )
  )) {
    throw new RuntimeException('Failed to move uploaded file.');
  }

  echo 'File is uploaded successfully.';
} catch (RuntimeException $e) {
  header("HTTP/1.0 500 Internal Server Error");
  echo $e->getMessage();
}
