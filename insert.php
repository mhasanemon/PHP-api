<?php
// SET HEADER
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//Including database and making object
require 'database.php';
$db_connection = new Database();
$conn = $db_connection->dbConnection();

//Get a data from request
$data = json_decode(file_get_contents("php://input"));

//create a message array and set empty
$msg['message'] = '';

//check if received data from request
if(isset($data->title) && isset($data->body) && isset($data->author)){
    //check data value is empty or not
    if(!empty($data->title) && !empty($data->body) && !empty($data->author)){
        $insert_query = "INSERT INTO `posts`(title,body,author) VALUES(:title,:body,:author)";

        $insert_stmt = $conn->prepare($insert_query);

        //Data binding
        $insert_stmt->bindValue(':title',htmlspecialchars(strip_tags($data->title)),PDO::PARAM_STR);
        $insert_stmt->bindValue(':body',htmlspecialchars(strip_tags($data->body)),PDO::PARAM_STR);
        $insert_stmt->bindValue(':author',htmlspecialchars(strip_tags($data->author)),PDO::PARAM_STR);

        if($insert_stmt->execute()){
            $msg['message'] = 'Data Inserted successfully';
        }
        else{
            $msg['message'] = 'Data not Inserted';
        }
    }
    else{
        $msg['message'] = 'Please fill all the fields |title,body,author';
    }
    //echo data in json format\
    echo json_encode($msg);
}