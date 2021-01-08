<?php
// SET HEADER
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");

//Including database and making object
require 'database.php';

$db_connection = new Database();
$conn = $db_connection->dbConnection();

//check get id parameter or not
if(isset($_GET['id'])){
    //if has id parameter
    $post_id = filter_var($_GET['id'],FILTER_VALIDATE_INT,[
        'options' => [
            'default' => 'all_posts',
            'min_range' => 1
        ]
    ]);
}
else{
    $post_id = 'all_posts';
}

//check if any post is available in database
$sql = is_numeric($post_id) ? "SELECT * FROM posts WHERE id='$post_id'" : "SELECT * FROM posts";

$stmt = $conn->prepare($sql);
$stmt->execute();

if($stmt->rowCount()>0){
    //create posts array
    $posts_array = [];

    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $post_data = [
            'id' => $row['id'],
            'title' => $row['title'],
            'body' => $row['body'],
            'author' => $row['author'],
            ];
        //push the data into our array
        array_push($posts_array,$post_data);
    }

    //show posts in json format
    echo json_encode($posts_array);
}
else{
    //there is no post found in our database
    echo json_encode(['message'=>'No post found']);
}

