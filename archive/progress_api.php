<?php
session_start();

if(isset($_POST['progress'])) {
    $_SESSION['download_progress'] = $_POST['progress'];
    echo "Progress Updated";
} else {
    echo "No progress data received";
}

