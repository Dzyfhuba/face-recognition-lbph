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
  'unknown', 
  'hafidz', 
  'rizky',
  'agung',
  'didi',
  'ferdi',
];

// $images = [
//   'images/segmentated/1689734916546.png',
//   'images/segmentated/1689821978506.png',
//   'images/segmentated/1689822604947.png',
//   'images/segmentated/1689823365480.png',
//   'images/segmentated/1689826848444.png',
// ];

if ($path === '/train' && $method === 'POST') {
  if (!isset($_FILES['image'])) {
    // print_r($_FILES);
    echo "image required";
    return;
  } else {
    move_uploaded_file($_FILES['image']['tmp_name'], "trained_image/".$_FILES["image"]["name"]);
  }

  $images = [];

  // var_dump($images);

  $status = trainNewModel($faceRecognizer, $faceClassifier, $images, $labels);
  print_r($status);
}