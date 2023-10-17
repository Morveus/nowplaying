<?php
// This script is dedicated to all my friends who like beautifully written code
// Because it is the exact opposite
// :wave: @dorsk


set_time_limit(5);
require('functions.php');

$currentFolder = __DIR__;
shell_exec("php $currentFolder/update.php > /dev/null 2>/dev/null &");

if(!file_exists($lastDataFile)) die("Server has been unresponsive before cache warmup");

require('process.php');
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

	/* h1, h2, h3, h4, h5, h6 {
	    font-size: 150%;
	} */

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
