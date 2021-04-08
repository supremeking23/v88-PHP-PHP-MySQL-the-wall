<?php 
session_start();
require_once("connection.php");
date_default_timezone_set('Asia/Manila');
if(isset($_POST["process_type"]) AND $_POST["process_type"] === "register") {

    function checkForNumericCharacter($str){
        $strArray = str_split($str);
        // $has_error = false;
        for($i = 0; $i < count($strArray); $i++){
            if(is_numeric($strArray[$i])){
                // $has_error = true;
                return TRUE;
                // break;
            }
        }
    }
    
    if(empty($_POST["first_name"])){
        $_SESSION["errors"][] = "First Name cannot be blank";
        $_SESSION["has_error_first_name"] = TRUE;
    }
    if(empty($_POST["last_name"])){
        $_SESSION["errors"][] = "Last Name cannot be blank";
        $_SESSION["has_error_last_name"] = TRUE;
    }
    if(empty($_POST["email"])){
        $_SESSION["errors"][] = "Email cannot be blank";
        $_SESSION["has_error_email"] = TRUE;
    }
    if(empty($_POST["password"])){
        $_SESSION["errors"][] = "Password cannot be blank";
        $_SESSION["has_error_password"] = TRUE;
    }
    if(empty($_POST["confirm_password"])){
        $_SESSION["errors"][] = "Confirm Password cannot be blank";
        $_SESSION["has_error_confirm_password"] = TRUE;
    }

    
    if(checkForNumericCharacter($_POST["first_name"])){
        $_SESSION["errors"][] = "First name cannot have a number";
        $_SESSION["has_error_first_name"] = TRUE;
    }

    if(checkForNumericCharacter($_POST["last_name"])){
        $_SESSION["errors"][] = "Last name cannot have a number";
        $_SESSION["has_error_last_name"] = TRUE;
    }

    if(!empty($_POST["first_name"]) AND !(strlen($_POST["first_name"]) > 2)){
        $_SESSION["errors"][] = "First name must be atleast 2 characters long";
        $_SESSION["has_error_first_name"] = TRUE;
    }

    if(!empty($_POST["last_name"]) AND !(strlen($_POST["last_name"]) > 2)){
        $_SESSION["errors"][] = "Last name must be atleast 2 characters long";
        $_SESSION["has_error_last_name"] = TRUE;
    }

    $email = escape_this_string($_POST["email"]);
    if(!empty($_POST["email"]) AND !(filter_var($email,FILTER_VALIDATE_EMAIL))){
        $_SESSION["errors"][] = "Email must be valid";
        $_SESSION["has_error_email"] = TRUE;
    }

    // 
    if((!empty($_POST["password"]) AND !empty($_POST["confirm_password"])) AND (strlen($_POST["password"]) !== strlen($_POST["confirm_password"]))) {
        $_SESSION["errors"][] = "password and confirm password does not match";
        $_SESSION["has_error_password"] = TRUE;
        $_SESSION["has_error_confirm_password"] = TRUE;
    }

    if((!empty($_POST["password"]) AND !(strlen($_POST["password"]) > 8))){
        $_SESSION["errors"][] = "password must be atleast 8 characters long";
        $_SESSION["has_error_password"] = TRUE;
    }


    if(isset($_SESSION["errors"]) AND count($_SESSION["errors"]) > 0){
        header("Location: index.php");
        die();
    }


    //sanitize field
    $first_name = escape_this_string($_POST["first_name"]);
    $last_name = escape_this_string($_POST["last_name"]);
    $email = escape_this_string($_POST["email"]);
    $password = escape_this_string($_POST["password"]);
    $confirm_password = escape_this_string($_POST["confirm_password"]);

    $salt = bin2hex(openssl_random_pseudo_bytes(22));
    $encrypted_password = md5($password . '' . $salt);

    //check if email is exist
    $query = "SELECT * FROM users WHERE email = '$email'";
    $run_query = fetch_record($query);
    if($run_query){
        $_SESSION["errors"][] = "Email already exist";
        header("Location: index.php");
    }else {
        $query = "INSERT INTO users(first_name,last_name,email,password,salt,created_at) VALUES ('$first_name','$last_name','$email','$encrypted_password', '$salt',NOW())";
        echo $query; 
        $run_query = run_mysql_query($query);
        if($run_query){
            echo $run_query;
            $_SESSION["first_name"] = $first_name;
            $_SESSION["email"] = $email;
            $_SESSION["user_id"] =  $run_query;
            
            header("Location: wall.php");
            //success
        }

    }



}else if(isset($_POST["process_type"]) AND $_POST["process_type"] === "login") {
    $email = escape_this_string($_POST["email"]);
    $password = escape_this_string($_POST["password"]);

     //check email if exist
     $query = "SELECT * FROM users WHERE email = '$email'";
     $user = fetch_record($query);

     if(empty($_POST["email"])){
        $_SESSION["errors"][] = "Email cannot be blank";
        $_SESSION["has_error_email"] = TRUE;
    }
    if(empty($_POST["password"])){
        $_SESSION["errors"][] = "Password cannot be blank";
        $_SESSION["has_error_password"] = TRUE;
    }

    if(!(empty($_POST["email"])) AND !(empty($_POST["password"]))){
        if(!empty($user)){
            $encrypted_password = md5($password . '' . $user["salt"]);
            if($user["password"] == $encrypted_password){
                $_SESSION["first_name"] = $user["first_name"];
                $_SESSION["user_id"] = $user["id"];
                header("Location: wall.php");
            }else{
                $_SESSION["errors"][] = "Incorrect Password";
                $_SESSION["has_error_password"] = TRUE;
                
            }
        }else {
            $_SESSION["errors"][] = "Incorrect Email";
            $_SESSION["has_error_email"] = TRUE;
           
        }
    }

    if(isset($_SESSION["errors"]) AND count($_SESSION["errors"]) > 0){
        header("Location: login.php");
        die();
    }


}else if(isset($_POST["process_type"]) AND $_POST["process_type"] === "post-message") {
    $message = escape_this_string($_POST["message"]);

    $query = "INSERT INTO messages(user_id,message,created_at) VALUES ({$_SESSION['user_id']},'$message',NOW())";
    $run_query = run_mysql_query($query);
    if($run_query){
        $_SESSION["add_message_success"] = "Message has been added successfully";
        header("Location: wall.php");
    }
 
    // echo "posthere" . $_SESSION["user_id"];
}else if(isset($_POST["process_type"]) AND $_POST["process_type"] === "post-comment") {
    $comment = escape_this_string($_POST["comment"]);
    $query = "INSERT INTO comments(message_id,user_id,comment,created_at) VALUES ({$_POST['message_id']},{$_SESSION['user_id']},'$comment',NOW())";
    $run_query = run_mysql_query($query);
    if($run_query){
        $_SESSION["add_comment_success"] = "Comment has been added successfully";
        header("Location: message.php?message_id={$_POST['message_id']}");
    }

}else if(isset($_POST["process_type"]) AND $_POST["process_type"] === "delete-message") {   

    $query_delete_message_from_comment = "DELETE FROM comments WHERE message_id = {$_POST['message_id']}";
    run_mysql_query($query_delete_message_from_comment);
   
    $query_delete_message = "DELETE FROM messages WHERE id = {$_POST['message_id']}";
    $run_query_delete_message = run_mysql_query($query_delete_message );
        
    $_SESSION["delete_message_success"] = "Message has been deleted successfully";
    header("Location: wall.php");
  
    

}else {
    header("Location: index.php");
}

?>