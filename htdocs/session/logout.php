<?php
include_once __DIR__ . "/../script/required.php";
session_unset();
echo "<html><head><title>Logged out</title></head><body><center><h1>Logged out... redirecting....</h1></center></body></html>";
redirect5s("/");
?>