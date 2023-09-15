<?php
session_start();

if(isset($_SESSION['download_progress'])) {
    echo $_SESSION['download_progress'];
} else {
    echo "0";
}
?>
