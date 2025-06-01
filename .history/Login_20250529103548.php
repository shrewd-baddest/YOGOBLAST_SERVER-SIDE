<?php
 
 
 header("Access-Control-Allow-Origin: http://localhost:5173");  
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Content-Type: application/json");

  $logins = json_decode(file_get_contents("php://input"),true);
  if ($logins === null) {
         echo " JSON data not received.";
         exit; 
 }
include('connect.php');
 $email=$logins['Emaili'];
 $Code=$logins['Code'];
 
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

$user=mysqli_fetch_assoc($result);
 
   // Verify the password with password_verify
   if (password_verify($Code, $user['passcode'])) {
    $userId = $user['ID'];

    // Store in sessions table if needed
  //   $insert = "UPDATE sessions
  //  SET user_id= ? WHERE id=14 ";
  $insert="INSERT INTO sessions(user_id)
  VALUES(?)";
    $stmt = mysqli_prepare($conn, $insert);
    mysqli_stmt_bind_param($stmt, 'i', $userId);
    mysqli_stmt_execute($stmt); 
    echo json_encode(["status" => "success"]);
    exit;
}
}
 
mysqli_close($conn);


?>