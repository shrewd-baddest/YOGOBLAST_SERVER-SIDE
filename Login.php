<?php

session_start();

  header("Access-Control-Allow-Origin:*");
  header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT");
  header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

  $logins = json_decode(file_get_contents("php://input"),true);
  if ($logins === null) {
         echo " JSON data not received.";
         exit; 
 }
include('connect.php');
 $email=$logins['Emaili'];
 $Code=$logins['Code'];
 $_session[user_Id]=$email; 
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

$original=mysqli_fetch_assoc($result);
 
   // Verify the password with password_verify
   if (password_verify($Code, $original['passcode'])) {
    // Password matches, send the user data as JSON
 
    echo json_encode(['status'=>'success','data'=>$original]);
} else {
    echo 'Incorrect password';
}
}
else{
    echo 'enter correct email';
}

mysqli_close($conn);


?>