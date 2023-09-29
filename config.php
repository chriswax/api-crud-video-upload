<?php
$DBhost = "localhost";
//$DBuser = "waxworks_wax";
//$DBpassword = "=MtsxDHFg~G=";
//$DBname = "waxworks_video";

$DBuser = "root";
$DBpassword = "";
$DBname = "hngx_video_upload";
$conn = mysqli_connect($DBhost, $DBuser, $DBpassword, $DBname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

include "function.php";