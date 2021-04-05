<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Registration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- <div div class="dim">
        error
    </div> -->
    <div class="container">

        <?php 
        if(isset($_SESSION["errors"])){
                foreach($_SESSION["errors"] as $errors):?>
                <div class="errors alert alert-danger">
                 
                   <?= $errors;?>
                 
                </div>
                <?php endforeach;
        }

        ?>
       
        
       
       <div class="cta">
            <h1>Learn to code by watching others</h1>
            <p>See how experienced developers solve problems in real-time. Watching scripted tutorials is great, but understanding how developers think is invaluable.</p>

          
       </div>

       <div class="form">
            <h2>Try it free 7 days then $20/mo. thereafter</h2>

            <form action="process.php" method="POST">
                <input type="hidden" name="process_type" value="login">
                <input type="text" name="email" placeholder="Email" value="" class="<?= (isset($_SESSION["has_error_email"])) ? "highlight":"" ?> ">
                <input type="password" name="password" placeholder="Password" value="" class="<?= (isset($_SESSION["has_error_password"])) ? "highlight":"" ?> ">
               
                <input type="submit" name="submit" value="Login">
                <p>Dont have an account ? <a href="index.php">Register here</a></p>
            </form>

        </div>

        <p class="created_by">Created By: Ivan Christian Jay Funcion</p>
    </div>
    
</body>
</html>
<?php 
unset($_SESSION["errors"]);
unset($_SESSION["first_name"]);
unset($_SESSION["email"]);
unset($_SESSION["has_error_email"]);
unset($_SESSION["has_error_password"]);

?>