<?php
set_time_limit(2);

$host = "192.168.1.213";
$url = "http://".$host."/api/v1/getState";

$lastTimeFile = "/tmp/lasttime";
$lastDataFile = "/tmp/lastdata";

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
    }
}

$data = json_decode(file_exists($lastDataFile) ? file_get_contents($lastDataFile) : '[]', true);

$status = (!file_exists($lastTimeFile) || (time() - filemtime($lastTimeFile)) > 60) ? "stopped" : $data['status'];
if(!file_exists($lastTimeFile)) die("Server has been unresponsive before cache warmup");

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

$img = 'http://'.$host.$albumart; // replace with your image URL
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
	<h5 id="info"><?php echo strtoupper($trackType) . " - $bitdepth/$samplerate " ; ?></h5>
    </div>

    <script>
    window.onload = function(){
        setTimeout(function(){ location.reload(); }, 7000);
    };
    </script>

</body>
</html>
