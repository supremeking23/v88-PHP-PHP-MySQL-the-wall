<?php 
session_start();
date_default_timezone_set('Asia/Manila');
require_once("connection.php");
if(!isset($_SESSION["first_name"])){
    header("Location: login.php");
}

$query = "SELECT messages.id as message_id,users.id as user_id,messages.message as message, CONCAT(users.first_name,' ', users.last_name) as full_name, messages.created_at FROM users INNER JOIN messages ON users.id = messages.user_id ORDER BY message_id DESC";
$messages = fetch_all($query);


function dateDifference($date_1  , $differenceFormat = '%y %m %d %h %i %s')
{
    $datetime1 = date_create($date_1);
    $datetime2 = date_create();
   
    $interval = date_diff($datetime1, $datetime2);
   
    return $interval->format($differenceFormat);
   
}
// https://www.php.net/manual/en/function.date-diff.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wall</title>
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
                <a href="logout.php">Logout</a>
            </div>
        </nav>

     
       
        <div class="main-content">

        <?php if(isset($_SESSION["delete_message_success"])):?>
            <h1 class="alert alert-danger"><?= $_SESSION["delete_message_success"]?></h1>
        <?php endif; ?>

        <?php if(isset($_SESSION["add_message_success"])):?>
            <h1 class="alert alert-success"><?= $_SESSION["add_message_success"]?></h1>
        <?php endif; ?>


            <form action="process.php" method="POST">
                <input type="hidden" name="process_type" value="post-message">
                <textarea name="message" id=""></textarea>
                <input type="submit" value="Post a mesage">
                <div class="clearfix"></div>
            </form>

            <div class="message-list">

            <?php foreach($messages as $message):
                 $date = date_create($message["created_at"]);
                 $format_date = date_format($date,"F dS Y");    
                //  echo dateDifference($message["created_at"]);
                 $scatter_date_info = explode(" ",dateDifference($message["created_at"]));

                 //check if minutes is < 60
                 $time = "";
                //  if($explode[0] >= 1){
                //      $time = "{$explode[0]} year(s) ago";
                //  }else if ($explode[1] < 13 AND $explode[2] > 32){
                //     $time = "{$explode[1]} months ago";
                //  }else if ($explode[2] < 32 AND $explode[3] > 13){
                //     $time = "{$explode[2]} days ago";
                //  }else if ($explode[3] < 13 AND $explode[4] > 61){
                //     $time = "{$explode[3]} hours ago";
                //  }else if ($explode[4] < 61 AND $explode[5] > 61){
                //     $time = "{$explode[4]} minutes ago";
                //  }else if ($explode[5] < 61){
                //     $time = "{$explode[5]} seconds ago";
                //  }else{
                //      $time ="sds";
                //  }
                
                $show_delete = FALSE;
                if((int) $scatter_date_info[0] >= 1){
                    $time = "{$scatter_date_info[0]} year(s) ago";
                }else if ((int) $scatter_date_info[1] >= 1){
                    $time = "{$scatter_date_info[1]} months ago";
                }else if ((int) $scatter_date_info[2] >=1){
                    $time = "{$scatter_date_info[2]} days ago";
                }else if ((int) $scatter_date_info[3] >= 1){
                    $time = "{$scatter_date_info[3]} hours ago";
                }else if ((int) $scatter_date_info[4] >= 1){
                    $time = "{$scatter_date_info[4]} minutes ago";
                    if((int) $scatter_date_info[4] <= 30 ){
                        $show_delete = TRUE;
                    }
                }else if ((int) $scatter_date_info[5] >=0){
                   $time = "{$scatter_date_info[5]} seconds ago";
                   $show_delete = TRUE;
                }else{
                    // $time ="sds";
                }

                
               

                //  var_dump($explode);
            ?>
                <div class="message">
                    <a  href="message.php?message_id=<?= $message["message_id"]?>">
                        <h2><?= $message["full_name"];?> - <?= $format_date;?></h2>
                    </a>
                    <p><?= $message["message"];?><?php echo $show_delete; ?></p>
                    <h5> posted: <?= $time;?></h5>

                    <?php if($show_delete): ?>
                        <?php if(isset($_SESSION["user_id"]) AND $_SESSION["user_id"] === $message["user_id"]):?>
                        <form action="process.php" method="POST">
                            <input type="hidden" name="process_type" value="delete-message">
                            <input type="hidden" name="message_id" value="<?= $message["message_id"]?>">
                            <input type="submit" class="delete-message" value="Delete message">
                        </form>
                        <?php endif; ?>
                    <?endif; ?>
                </div>
            <?php endforeach;?>
            </div>
        </div>
    </div>
</body>
</html>

<?php 
unset($_SESSION["delete_message_success"]);
unset($_SESSION["add_message_success"]);
?>