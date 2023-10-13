<?php
// This script is dedicated to all my friends who like beautifully written code
// Because it is the exact opposite
// :wave: @dorsk

set_time_limit(2);

$host = "192.168.1.213";
$url = "http://".$host."/api/v1/getState";

$lastTimeFile = "/tmp/lasttime";
$lastDataFile = "/tmp/lastdata";
$curl_ok = false;
if (!file_exists($lastTimeFile) || (time() - filemtime($lastTimeFile)) > 10) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 1);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        $result = false;
    }
    curl_close($ch);

    if ($result !== false) {
        file_put_contents($lastDataFile, $result);
        touch($lastTimeFile);
        $curl_ok = true;
    }
}

$data = json_decode(file_exists($lastDataFile) ? file_get_contents($lastDataFile) : '[]', true);

$status = (!file_exists($lastTimeFile) || (time() - filemtime($lastTimeFile)) > 60) ? "stopped" : $data['status'];
if(!file_exists($lastTimeFile)) die("Server has been unresponsive before cache warmup");

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

// Now we process the data
if($bitdepth == ""){
  $technical = "$trackType - $bitrate";
}elseif(strtolower($trackType) == "youtube"){
  $technical = "$samplerate";
}else{
  $technical = "$trackType - $bitdepth/$samplerate";
}

$technical .= "$channels channels";

if (strpos($albumart, "http") !== false) {
  $img = $albumart;
} else {
  $img = 'http://'.$host.$albumart;
}

$md5 = md5($img);
$imgfile = "/tmp/image.$md5";

if($curl_ok && !is_file($imgfile)){
  $ctx = stream_context_create(array(
      'http' => array(
          'timeout' => 2
          )
      )
  );

  $data = file_get_contents($img, false, $ctx);
  $base64 = 'data:image/jpeg;base64,' . base64_encode($data);
  file_put_contents($imgfile, $base64);
}else{
  $base64 = file_get_contents($imgfile);
}

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

/* echo "Artist: $artist\n";
echo "Album: $album\n";
echo "Song: $title\n";
echo "Album Art: $albumart\n"; */
?>

<!DOCTYPE html>
<html>
<head>
    <title>Moon - <?php echo $pagetitle; ?></title>
    <link rel="icon" type="image/png" sizes="16x16" href="./icon-16.png">
    <link rel="icon" type="image/png" sizes="32x32" href="./icon-32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="./icon-96.png">

    <style>
        body {
            background-color: black;
            color: white;
            font-family: Arial, sans-serif;
	    font-size: 1em;
        }
        #nowPlaying {
            text-align: center;
	    margin-top: 10%;
        }
        #albumArt {
            width: 200px;
            height: 200px;
            object-fit: cover;
        }

	h1, h2, h3, h4, h5, h6 {
	    font-size: 150%;
	}


	.pause { color: orange; }
	.stop { color: red; }
    </style>
</head>

<body>
    <div id="nowPlaying">
	<h3 class="<?php echo $status; ?>"><?php echo $current_status; ?></h3>
        <img id="albumArt" src="<?php echo $base64; ?>" alt="Album Art">
        <h2 id="song"><?php echo $title; ?></h2>
        <h3 id="artist"><?php echo $artist; ?></h3>
        <h4 id="album"><?php echo $album; ?></h4>
	<h5 id="info"><?php echo $technical; ?></h5>
    </div>

    <script>
    window.onload = function(){
        setTimeout(function(){ location.reload(); }, 7000);
    };
    </script>

</body>
</html>
