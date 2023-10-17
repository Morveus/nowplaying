<?php
// This script is dedicated to all my friends who like beautifully written code
// Because it is the exact opposite
// :wave: @dorsk

if (basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"])) {
    exit('This script cannot be run directly.');
}

ob_start();

$host = "streamer.morve.us";
$url = "http://".$host."/api/v1/getState";

$tmpdir = "/tmp";
$lastDataFile = $tmpdir . '/lastdata';
$albumartfile = $tmpdir . '/albumart';
$semaphore = $tmpdir . '/semaphore';

$time = time();
$curl_ok = false;

ob_end_clean();
