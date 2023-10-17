<?php

if (basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"])) {
  exit('This script cannot be run directly.');
}

$data = json_decode(file_exists($lastDataFile) ? file_get_contents($lastDataFile) : '[]', true);
$status = (!file_exists($lastDataFile) || ($time - filemtime($lastDataFile)) > 60) ? "stopped" : $data['status'];

ob_start();
$position = $data['position'];
$title = $data['title'];
$artist = $data['artist'];
$album = $data['album'];
$albumart = $data['albumart'];
$uri = $data['uri'];
$trackType = $data['trackType'];
$seek = $data['seek'];
$duration = $data['duration'];
$samplerate = $data['samplerate'];
$bitdepth = $data['bitdepth'];
$channels = $data['channels'];
$random = $data['random'];
$repeat = $data['repeat'];
$repeatSingle = $data['repeatSingle'];
$consume = $data['consume'];
$volume = $data['volume'];
$dbVolume = $data['dbVolume'];
$disableVolumeControl = $data['disableVolumeControl'];
$mute = $data['mute'];
$stream = $data['stream'];
$updatedb = $data['updatedb'];
$volatile = $data['volatile'];
$service = $data['service'];
$bitrate = $data['bitrate'];
ob_end_clean();

$trackType = ucfirst($trackType);
if(strlen($trackType) < 5){ // yes it's horribly bad code
  $trackType = strtoupper($trackType);
}

switch($channels){
  case 1:
    $ch = "mono";
    break;
  case 2:
    $ch = "Stereo";
    break;
  case 3:
    $ch = "3 channels";
    break;
  case 4:
    $ch = "Quadiphonic";
    break;
  default:
    $ch = "Multichannel";
    break;
}

// Now we process the data
if($bitdepth == ""){
  $technical = "$trackType - $bitrate";
}else{
  $technical = "$trackType - $bitdepth / $samplerate";
}

if(strtolower($trackType) == "youtube"){
  $technical = "$samplerate";
}

$technical .= " - $ch";

if (strpos($albumart, "http") !== false) {
  $img = $albumart;
} else {
  $img = 'http://'.$host.$albumart;
}

$imgfile = $albumartfile;
$base64 = file_get_contents($imgfile);

$status_class = "";
$pagetitle = "Now playing";
switch($status){
  case "play":
    $current_status = "Now Playing";
    break;
  case "pause":
    $current_status = "Paused";
    break;
  default:
    $current_status = "Stopped. Last played:";
    $status = "stop";
    $pagetitle = "Last played";
    break;
}

?>
