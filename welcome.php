<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Edwin Changwe">
    <title>Nyumba Yanga | Register</title>
   
    <!--CSS Styling-->
    <link rel="stylesheet" href="./css/style.css">
    <!--Bootstrap-->
    <!-- Latest compiled and minified CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Latest compiled JavaScript -->
    <script src="js/bootstrap.bundle.min.js"></script>

    <!--Inline Style for Sign-up form-->
    
</head>
<body>
 <header>
        <div class="container">
            <div id="branding">
                <h1><span class="highlight">Nyumba </span> Yanga</h1>
            </div>
            <nav>
                <ul>
                    <li class = "current" ><a href="index.html">Home</a></li>
                    <li><a href="logout.php">Sign Out</a></li>
                    
                </ul>
            </nav>
        </div>
    </header>
	

    <h1 class="my-5">Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to our site.</h1>
    <p>
        
    </p>
</body>
</html>