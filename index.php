<?php
require_once 'train.php';

use CV\Face\LBPHFaceRecognizer, CV\CascadeClassifier, CV\Scalar, CV\Point;

// face by lbpcascade_frontalface
$faceClassifier = new CascadeClassifier();
$faceClassifier->load('lbpcascade_frontalface.xml');

$faceRecognizer = LBPHFaceRecognizer::create();

$path = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

$labels = [
  // 'unknown',
  // 'hafidz',
  // 'rizky',
  // 'agung',
  // 'didi',
  // 'ferdi',
];

// $images = [
//   'images/segmentated/1689734916546.png',
//   'images/segmentated/1689821978506.png',
//   'images/segmentated/1689822604947.png',
//   'images/segmentated/1689823365480.png',
//   'images/segmentated/1689826848444.png',
// ];

if ($path === '/train' && $method === 'POST') {
  $file_uploaded = "trainedImages/" . $_FILES["image"]["name"];
  if (!isset($_FILES['image'])) {
    // print_r($_FILES);
    echo "image required";
    http_response_code(400);
    return;
  } else {
    if (!file_exists('trainedImages')) mkdir('trainedImages');
    move_uploaded_file($_FILES['image']['tmp_name'], $file_uploaded);
  }
  
  $images = ['images/segmentated/1689734916546.png'];
  $labels = [$_POST['name']];
  echo "exist: ". file_exists($images[0]);
  echo "\n {$file_uploaded}";

  $faceRecognizer = trainNewModel($faceRecognizer, $faceClassifier, $images, $labels);

  http_response_code(201);
  echo json_encode([
    'message' => 'image trained'
  ]);
}

if ($path === '/predict' && $method === 'POST') {
  if (!isset($_FILES['image'])) {
    // print_r($_FILES);
    echo "image required";
    http_response_code(400);
    return;
  } else {
    if (!file_exists('predicted_images')) mkdir('predicted_images');
    move_uploaded_file($_FILES['image']['tmp_name'], "predicted_images/" . $_FILES["image"]["name"]);
  }
}