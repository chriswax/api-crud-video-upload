<?php
//Initialize and get id passed for getUser, Update and delete routes
global $uploadPath, $baseUrl, $gDate;

//get time zone
date_default_timezone_set('Africa/Lagos');
$gDate = date('Y-m-d H:i:s');

$baseUrl = $_SERVER['HTTP_HOST']."/";
//upload paths
$uploadPath = "uploads/";
$thumbnailPath = "thumbnail/";

function esc($value)
{
    global $conn;
    $val = trim($value);
    $val = mysqli_real_escape_string($conn, $val);
    $val = strip_tags($val);
    return $val;
}

$allowedExtensions =array(
    "mp4",
    "webm",
    "avi",
    "flv"
);

$allowedImageExtensions =array(
    "bmp",
    "jpeg",
    "gif",
    "jpg",
    "png"
);


////////----converts string to slug eg 1 am wax to i-am-wax ---///////////////////
function makeSlug($value){
	$slug = strtolower($value);
	$slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $slug);
	return $slug;
}

function videoSlug($value){
	$slug = strtolower($value);
	$slug = preg_replace('/[^A-Za-z0-9-]+/', '_', $slug);
	return $slug;
}