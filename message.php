<?php 
session_start();
require_once("connection.php");
if(!isset($_SESSION["first_name"]) OR !isset($_GET["message_id"])){
    header("Location: login.php");
}


$get_message_by_message_id = "SELECT messages.id as message_id,messages.message as message, CONCAT(users.first_name,' ', users.last_name) as full_name, messages.created_at FROM users INNER JOIN messages ON users.id = messages.user_id WHERE messages.id = {$_GET["message_id"]}";
$message = fetch_record($get_message_by_message_id);

$date = date_create($message["created_at"]);
$message_format_date = date_format($date,"F dS Y");    

$get_comments_by_message_id = "SELECT comments.comment as comment,comments.created_at as created_comment_date,CONCAT(users.first_name,' ', users.last_name) as full_name FROM comments INNER JOIN users INNER JOIN messages ON comments.message_id = messages.id AND users.id = comments.user_id  WHERE messages.id = {$_GET["message_id"]} ORDER BY comments.id ASC"; 
$comments = fetch_all($get_comments_by_message_id);



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wall | Comment Section</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container-full">
        <nav>
            <a href="wall.php">
                <h1>CodingDojo Wall</h1>
            </a>
            <div class="">
                <h1> Welcome <?= $_SESSION["first_name"];?></h1>
                <a href="login.php">Logout</a>
            </div>
        </nav>


       
        <div class="main-content">
            <?php if(isset($_SESSION["add_comment_success"])):?>
                <h1 class="alert alert-success"><?= $_SESSION["add_comment_success"]?></h1>
            <?php endif; ?>


            <div class="message">
                <h2><?= $message["full_name"]?> - <?= $message_format_date;?></h2>
                <p><?= $message["message"]?></p>
            </div>

            <div class="comments">
            <?php foreach($comments as $comment):?>
                <div>
                    <h2><?= $comment["full_name"]?></h2>
                    <p><?= $comment["comment"]?></p>
                </div>
            <?php endforeach;?>
            </div>


            <form action="process.php" method="POST">
                <input type="hidden" name="process_type" value="post-comment">
                <input type="hidden" name="message_id" value="<?= $_GET["message_id"]?>">
                <textarea name="comment" id=""></textarea>
                <input type="submit" value="Submit">
                <div class="clearfix"></div>
            </form>
        </div>
    </div>
</body>
</html>

<?php unset($_SESSION["add_comment_success"]);
?>