<?php 
session_destroy();
if(!isset($_SESSION) AND empty($_SESSION)){
    header("Location: login.php");
}
?>