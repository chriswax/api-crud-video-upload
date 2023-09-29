<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once "config.php";
global $conn;

//get the Request method sent from client e.g POST, GET, PUT and DELETE
$allow_method = '';
if (isset($_SERVER['REQUEST_METHOD'])) {
    $allow_method = $_SERVER['REQUEST_METHOD'];
}


//call header functions
header("Content-Type: application/json");
header("Acess-Control-Allow-Origin: *");
header("Acess-Control-Allow-Methods: $allow_method");
header("Acess-Control-Allow-Headers: Acess-Control-Allow-Headers,Content-Type, Acess-Control-Allow-Methods, Authorization");


###########################################################################################################################
			/////////////////////////////////////////// Start of upload Video Functions ////////////////////////////////////
##########################################################################################################################
function uploadVideo()
{
    global $conn, $uploadPath,$baseUrl , $allowedExtensions, $allowedImageExtensions, $gDate, $thumbnailPath;
    $result =array(); 
    $thumbnail = null;
   if(isset($_FILES["video"]["name"]) && $_FILES["video"]["name"] != ""){     

        $videoName = $_FILES["video"]["name"];
        $tmpName = $_FILES["video"]["tmp_name"];
        $videoSize = $_FILES["video"]["size"];
        $tmpName = $_FILES["video"]["tmp_name"];
        $error = $_FILES["video"]["error"]; 

        if($error === 0){
            $videoExtension = strtolower(pathinfo($videoName, PATHINFO_EXTENSION));  //get file extension and convert to lower case 
            //get video name without extension
            $videoTitle = basename($videoName, ".". $videoExtension);   

            //////////////////////////////////get other external attributes
            $username = isset($_POST["username"]) && $_POST["username"] != " " ? esc($_POST["username"]) : "default";
            $title = isset($_POST["title"]) && $_POST["title"] != " " ? esc($_POST["title"]) : esc($videoTitle);
            $description = isset($_POST["description"]) && $_POST["description"] != " " ? esc($_POST["description"]) : null;
            $fileName = esc($videoTitle);
            $slug = makeSlug($videoTitle);
            $transcription = null;

             //upload thumbnail if exist
             if(isset($_FILES["thumbnail"]["name"]) && $_FILES["thumbnail"]["name"] != ""){
                if($_FILES['thumbnail']['size'] / 1024 <= 5120) { // check that size is not more than5MB
                    //check that extension is allowed
                    if(in_array(strtolower(pathinfo($_FILES["thumbnail"]["name"], PATHINFO_EXTENSION)), $allowedImageExtensions)){  
                        $thumbnailName = uniqid().$_FILES["thumbnail"]["name"];
                        $thumbnailTmpName = $_FILES["thumbnail"]["tmp_name"];
                        $thumbnailPath = $thumbnailPath.$thumbnailName;
                        if(move_uploaded_file($thumbnailTmpName, $thumbnailPath)){
                            $thumbnail =$thumbnailPath;
                        }else{
                            $thumbnail = null;
                        }
                    }
                }
            }


            if(in_array($videoExtension, $allowedExtensions)){
                //title could be input from user or video name without extension
                $newVideoName = uniqid()."_".videoSlug($title).".".$videoExtension;
                $uploadPath = $uploadPath.$newVideoName;
                move_uploaded_file($tmpName, $uploadPath);

                //insert into Database
                $sql = "INSERT INTO video (username, title, videoUrl, description, fileName, fileSize, thumbnail, slug, transcription, createdAt ) VALUES ('$username', '$title', '$uploadPath', '$description', '$fileName', '$videoSize', '$thumbnail', '$slug', '$transcription', '$gDate')";
                mysqli_query($conn, $sql) or die(mysqli_error($conn));
                $id = mysqli_insert_id($conn);

                $id = mysqli_insert_id($conn);

                http_response_code(201);
                $result = [
                            "message" => "Video recording uploaded Successfully",
                            "statusCode" => 201,
                             "data"=>[
                                "id"=>$id,
                                "username"=>$username,
                                "title"=>$title,
                                "url"=>$baseUrl.$uploadPath,
                                "description"=>$description,
                                "fileName"=>$fileName,
                                "fileSize"=>$videoSize,
                                "thumbnail"=>$thumbnail == null ? $thumbnail : $baseUrl.$thumbnail,
                                "slug"=>$slug,
                                "transcription"=>$transcription,
                                "createdAt"=>$gDate,
                            ]
                        ];
            }else{
                http_response_code(501);
                $result = [
                            "message" => "Video extension invalid or not allowed",
                            "status_code" => 501,
                            "data"=>null
                        ];
            }
        }else{
            http_response_code(400);
                $result = [
                            "message" => $error,
                            "status_code" => 400,
                            "data"=>null
                        ];
        }
   }else{
        http_response_code(400);
        $result = [
                    "message" => "Video to upload is required",
                    "status_code" => 400,
                    "data"=>null
                ];
   }
   echo  json_encode($result);
}


function getVideos()
{
    global $conn, $baseUrl;
    $data =[];
    $rows = array();
    $id ="";
    if(isset($_GET["id"])){
        $id = esc($_GET["id"]); //the id can be either username, slug or id
    }  
    if($id == "" || $id == 0){
        $result = mysqli_query($conn, "SELECT * FROM video");  //get all videos if no parameter is passed
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
                $videoData =[
                    "id"=>$row['id'],
                    "username"=>$row['username'],
                    "title"=>$row['title'],
                    "url"=>$baseUrl.$row['videoUrl'],
                    "description"=>$row['description'],
                    "fileName"=>$row['fileName'],
                    "fileSize"=>$row['fileSize'],
                    "thumbnail"=>$row['thumbnail'] == null ? $row['thumbnail'] : $baseUrl.$row['thumbnail'],
                    "slug"=>$row['slug'],
                    "transcription"=>$row['transcription'],
                    "createdAt"=>$row['createdAt'],
                ];
                $rows[] = $videoData;
            }
            http_response_code(200);
            $data = [
                "message" => "Video(s) Fetched Successfully",
                "status_code" => 200,
                "data"=>$rows
            ];
        }else{
            http_response_code(404);
            $data = [
                "message" => "No Video Found!",
                "status_code" => 404,
                "data"=>null
            ];
        }
    }else{
        $result = mysqli_query($conn,"SELECT * FROM video WHERE id='$id' OR username='$id' OR slug='$id'");
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_array($result)){
                $videoData =[
                    "id"=>$row['id'],
                    "username"=>$row['username'],
                    "title"=>$row['title'],
                    "url"=>$baseUrl.$row['videoUrl'],
                    "description"=>$row['description'],
                    "fileName"=>$row['fileName'],
                    "fileSize"=>$row['fileSize'],
                    "thumbnail"=>$row['thumbnail'] == null ? $row['thumbnail'] : $baseUrl.$row['thumbnail'],
                    "slug"=>$row['slug'],
                    "transcription"=>$row['transcription'],
                    "createdAt"=>$row['createdAt'],
                ];
                $rows[] = $videoData;
            }
            http_response_code(200);
            $data = [
                "message" => "Video(s) to upload is required!",
                "status_code" => 200,
                "data"=>$rows
            ];
        }else{
            http_response_code(404);
            $data = [
                "message" => "No Video Found",
                "status_code" => 404,
                "data"=>null
            ];
        }
    }

    echo json_encode($data);
}


function deleteVideo()
{
    global $conn, $baseUrl, $uploadPath, $thumbnailPath;
    $data =[];
    $rows = array();
    $id ="";
    if(isset($_GET["id"]) && !empty($_GET["id"])){
        $id = esc($_GET["id"]); //the id can be either username, slug or id
        //check if Id exist
        $check = mysqli_query($conn, "SELECT * FROM video WHERE id='$id'");
        if(mysqli_num_rows($check) > 0){
            $getRow = mysqli_fetch_array($check);
            $videoPath = $getRow["videoUrl"];
            $thumbnailPath = $getRow["thumbnail"];
            $sqlDelete = mysqli_query($conn, "DELETE FROM video WHERE id ='$id'");
            if($sqlDelete){
                //delete the video and image
            
                unlink($videoPath);
                unlink($thumbnailPath);
                http_response_code(200);
                $data = [
                    "message" => "Video Deleted Successfully",
                    "status_code" => 200,
                    "data"=>null
                ];
            }else{
                http_response_code(500);
                $data = [
                    "message" => "Error occured, Video not deleted",
                    "status_code" => 500,
                    "data"=>null
                ];
            } 
        }else{
            http_response_code(404);
            $data = [
                "message" => "Video with Id is not Found",
                "status_code" => 404,
                "data"=>null
            ];
        }
    } else{
        http_response_code(400);
        $data = [
            "message" => "Bad Request, Video Id required",
            "status_code" => 400,
            "data"=>null
        ];
    }
    echo json_encode($data);
}

//call the API functions based on the the request method type
switch ($allow_method) {
    case "GET":   
        getVideos();
        break;
    case "POST":
        uploadVideo();
        break;
    case "DELETE":
        deleteVideo();
        break;
}
