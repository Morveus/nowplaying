<?php
// This script is dedicated to all my friends who like beautifully written code
// Because it is the exact opposite
// :wave: @dorsk

require('vars.php');

if (basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"])) {
  exit('This script cannot be run directly.');
}

ob_start();

function is_running() {
    global $semaphore;
    if (file_exists($semaphore)) {
        if (time() - filemtime($semaphore) < 10) {
            return true;
        } else {
            delete_semaphore();
        }
    }
    return false;
}

function create_semaphore() {
    global $semaphore;
    file_put_contents($semaphore, '');
}

function delete_semaphore() {
    global $semaphore;
    unlink($semaphore);
}

ob_end_clean();
