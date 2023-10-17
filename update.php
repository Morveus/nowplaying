<?php
// This script is dedicated to all my friends who like beautifully written code
// Because it is the exact opposite
// :wave: @dorsk
ini_set('memory_limit', '1G');

if(php_sapi_name() != 'cli') {
  header("Location: /");
  die();
}

set_time_limit(5);

require('functions.php');


echo "Checking if the script is already running... \n";
if(is_running()) die();

echo "Creating a semaphore... \n";
create_semaphore();

echo "reload! ... \n";
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 4);
curl_setopt($ch, CURLOPT_TIMEOUT, 4);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    $result = false;
}
curl_close($ch);

if ($result !== false) {
    file_put_contents($lastDataFile, $result);

    $data = json_decode(file_get_contents($lastDataFile), true);
    $albumart = $data['albumart'];

    if (strpos($albumart, "http") !== false) {
      $imgurl = $albumart;
    } else {
      $imgurl = 'http://'.$host.$albumart;
    }

    $ctx = stream_context_create(array(
        'http' => array(
            'timeout' => 2
            )
        )
    );

    $albumartdata = file_get_contents($imgurl, false, $ctx);
    $base64 = 'data:image/jpeg;base64,' . base64_encode($albumartdata);
    file_put_contents($albumartfile, $base64);

    print_r($data);
    echo "Creating data file...\n";
    $curl_ok = true;
}

echo "Deleting the semaphore...\n ";
delete_semaphore();
