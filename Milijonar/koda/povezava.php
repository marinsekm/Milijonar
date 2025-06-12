<?php
/*
povezava na strežnik
*/

$host='localhost';
$user='root';
$password='';
$database='milijonar';

$link=mysqli_connect($host, $user, $password, $database)
        or die("Povezovanje ni mogoče.");

mysqli_set_charset($link, "utf8");