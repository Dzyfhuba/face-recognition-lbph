<?php


use function CV\{imread, cvtColor, equalizeHist};
use const CV\{COLOR_BGR2GRAY};

require_once 'ignite.php';


function trainNewModel($faceRecognizer, $faceClassifier, Array $images, Array $labels)
{
  $faceImages = $faceLabels = [];
  foreach ($images as $key => $image) {
    // if (!$key) continue;
    echo $image;
    $src = imread($image);
    $gray = cvtColor($src, COLOR_BGR2GRAY);
    $faces = [];
    $faceClassifier->detectMultiScale($gray, $faces);
    //var_export($faces);
    equalizeHist($gray, $gray);
    foreach ($faces as $k => $face) {
      $faceImages[] = $gray->getImageROI($face); // face coordinates to image
      $faceLabels[] = $key + 1; // me
      cv\imwrite("results/recognize_face_by_lbph_me$k-$key.jpg", $gray->getImageROI($face));
    }

    $faceRecognizer->train($faceImages, $faceLabels);
  }
  $faceRecognizer->update($faceImages, $faceLabels);

  $faceRecognizer->write('results/lbph_model.xml');
  
  return $faceRecognizer;
}