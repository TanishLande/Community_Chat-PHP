<?php


session_start();

echo'Wait a minute...';

session_destroy();
header("Location: index.php");

?>