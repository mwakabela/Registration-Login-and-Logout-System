<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$firstname = $lastname = $username = $password = $confirm_password = "";
$firstname_err = $lastname_err = $username_err = $password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

     if (empty(trim($_POST["firstname"]))) {
    $firstname_err = "firstname is required";
  } else {
    $firstname =($_POST["firstname"]);
    // check if firstname only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z-' ]*$/",$firstname)) {
      $firstname_err = "Only letters and white space allowed";
    }
    }
	
	if (empty(trim($_POST["lastname"]))) {
    $lastname_err = "lastname is required";
  } else {
    $lastname = ($_POST["lastname"]);
    // check if lastname only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z-' ]*$/",$lastname)) {
      $lastnameErr = "Only letters and white space allowed";
    }
  }
  
    
	
	// Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (firstname, lastname, username, password) VALUES (?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssss", $param_firstname, $param_lastname, $param_username, $param_password);
            
            // Set parameters
			$param_firstname = $firstname;
			$param_lastname = $lastname;
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <!--CSS Styling-->
    <link rel="stylesheet" href="./css/style.css">
    <!--Bootstrap-->
    <!-- Latest compiled and minified CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Latest compiled JavaScript -->
    <script src="js/bootstrap.bundle.min.js"></script>

    <!--Inline Style for Sign-up form-->
    <style>
        .container { 
        margin-top:8%;
        margin-bottom:8%;
        width: 45%;
}
        .form-control {
            width:75%;
        }
    </style>
</head>
<body>

    <div class="container"
    style="
    border:1px solid lightgray;
    background: ghostwhite;
    padding: 45px; 
    margin-right: auto;
    margin-left: auto;
    border-radius: 10px;
    
    ">
            <h2 class="text-2xl font-bold uppercase mb-1 text-center">
                Register
            </h2>
            <p class="mb-4 te text-center">Create an account</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="mb-3 mt-3">
                <label for="firstname">First Name</label>
                <input type="text" name="firstname" class="form-control <?php echo (!empty($firstname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $firstname; ?>" >
				<span class="invalid-feedback"><?php echo $firstname_err; ?></span>
            </div>
            <div class="mb-3 mt-3">
                <label for="lastname">Last Name</label>
                <input type="text" name="lastname" class="form-control <?php echo (!empty($lastname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $lastname; ?>" >
				<span class="invalid-feedback"><?php echo $lastname_err; ?></span>
            </div>
        <div class="mb-3 mt-3">
            <label for="username">Username</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
        </div>
        <div class="mb-3">
            <label for="password">Password</label>
            <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
        </div>
        <div class="mb-3">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
        </div>
        <button type="submit" class="btn btn-danger" style=>Submit</button>
        <div class="mb-3 mt-3">
            <p> Already have an account? <a href="login.php" style="color:#e8491d;">Login</a>
        </form>
    </div>
</body>
</html>