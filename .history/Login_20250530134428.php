<?php
include ('session_config.php');
header("Access-Control-Allow-Origin: http://localhost:5173");  
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Content-Type: application/json");

  $logins = json_decode(file_get_contents("php://input"),true);
   if ($logins === null) {
     echo json_encode(["status" => "error", "message" => "JSON data not received."]);
     exit;
  }
include('connect.php');
 $email=$logins['Emaili'];
 $Code=$logins['Code'];
//   $email='1@gmail.com';
//     $Code='123456';
//  echo  $_SESSION['user'];
$sql="SELECT * FROM person
WHERE  email=?";
 
 $stmt = mysqli_prepare($conn, $sql);

 // Bind the parameters (s for string type)
 mysqli_stmt_bind_param($stmt, 's', $email);
 
 // Execute the query
 mysqli_stmt_execute($stmt);
 
 // Get the result
 $result = mysqli_stmt_get_result($stmt);

 
//  $result=mysqli_query($conn,$sql);
 
 
if(mysqli_num_rows($result) > 0){
    $user = mysqli_fetch_assoc($result);
    if (password_verify($Code, $user['passcode'])) {
        $userId = $user['ID'];
        session_regenerate_id(true);
        $_SESSION["userId"] = $userId;
        // echo $_SESSION["userId"];
        $insert = "INSERT INTO sessions(user_id) VALUES(?)";
        $stmt = mysqli_prepare($conn, $insert);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'i', $userId);
            if (mysqli_stmt_execute($stmt)) {
                echo json_encode(["status" => "success"]);
                exit;
            } else {
                echo json_encode(["status" => "error", "message" => "Session insert failed."]);
                exit;
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Session prepare failed."]);
            exit;
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid credentials."]);
        exit;
    }
} 
 
mysqli_close($conn);


?>