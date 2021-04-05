<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The wall</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- <div div class="dim">
        error
    </div> -->
    <div class="container">

        <?php 
        if(isset($_SESSION["errors"])){
                foreach($_SESSION["errors"] as $error):?>
                <div class="errors alert alert-danger">
                    <?= $error;?>
                </div>
                <?php endforeach;
        }

        ?>
       
        
       
       <div class="cta">
            <h1>CodingDojo Wall</h1>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquid excepturi earum dolores dolorem, non consectetur quae est! Fugiat nostrum asperiores iure sed? Quaerat delectus sit nemo id, incidunt reprehenderit eveniet.</p>

          
       </div>

       <div class="form">
            <h2>Registration Form</h2>

            <form action="process.php" method="POST">
                <input type="hidden" name="process_type" value="register" >
                <input type="text" name="first_name" placeholder="First Name" value="" class="<?= (isset($_SESSION["has_error_first_name"])) ? "highlight":"" ?>">
                <input type="text" name="last_name" placeholder="Last Name" value="" class="<?= (isset($_SESSION["has_error_last_name"])) ? "highlight":"" ?> ">
                <input type="text" name="email" placeholder="Email" value="" class="<?= (isset($_SESSION["has_error_email"])) ? "highlight":"" ?> ">
                <input type="password" name="password" placeholder="Password" value="" class="<?= (isset($_SESSION["has_error_password"])) ? "highlight":"" ?> "> 
                <input type="password" name="confirm_password" placeholder="Confirm Password" value="" class="<?= (isset($_SESSION["has_error_confirm_password"])) ? "highlight":"" ?> ">
                <input type="submit" name="submit" value="Register">
                <p>Already registered ? <a href="login.php">Login here</a></p>
            </form>

        </div>

        <p class="created_by">Created By: Ivan Christian Jay Funcion</p>
    </div>
    
</body>
</html>
<?php unset($_SESSION["errors"]);
unset($_SESSION["has_error_first_name"]);
unset($_SESSION["has_error_last_name"]);
unset($_SESSION["has_error_email"]);
unset($_SESSION["has_error_password"]);
unset($_SESSION["has_error_confirm_password"]);

?>