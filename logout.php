<?php 
session_start();
session_destroy();
// if(!isset($_SESSION) AND empty($_SESSION)){
//     header("Location: login.php");
// }

header("Location: login.php");
?>