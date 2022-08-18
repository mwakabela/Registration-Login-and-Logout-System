<?php
	require_once "config.php";
	
	$resetPasswordUsername = '';
	$resetPasswordPassword1 = '';
	$resetPasswordPassword2 = '';
	$hashedPassword = '';
	
	if(isset($_POST['resetPasswordUsername'])){
		$resetPasswordUsername = htmlentities($_POST['resetPasswordUsername']);
		$resetPasswordPassword1 = htmlentities($_POST['resetPasswordPassword1']);
		$resetPasswordPassword2 = htmlentities($_POST['resetPasswordPassword2']);
		
		if(!empty($resetPasswordUsername) && !empty($resetPasswordPassword1) && !empty($resetPasswordPassword2)){
			
			// Check if username is empty
			if($resetPasswordUsername == ''){
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Please enter your username.</div>';
				exit();
			}
			
			// Check if passwords are empty
			if($resetPasswordPassword1 == '' || $resetPasswordPassword2 == ''){
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Please enter both passwords.</div>';
				exit();
			}
			
			// Check if username is available
			$usernameCheckingSql = 'SELECT * FROM user WHERE username = :username';
			$usernameCheckingStatement = $conn->prepare($usernameCheckingSql);
			$usernameCheckingStatement->execute(['username' => $resetPasswordUsername]);
			
			if($usernameCheckingStatement->rowCount() < 1){
				// Username doesn't exist. Hence can't reset password
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Username does not exist.</div>';
				exit();
			} else {
				// Check if passwords are equal
				if($resetPasswordPassword1 !== $resetPasswordPassword2){
					echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Passwords do not match.</div>';
					exit();
				} else {
					// Start UPDATING password to DB
					// Encrypt the password
					$hashedPassword = md5($resetPasswordPassword1);
					$updatePasswordSql = 'UPDATE user SET password = :password WHERE username = :username';
					$updatePasswordStatement = $conn->prepare($updatePasswordSql);
					$updatePasswordStatement->execute(['password' => $hashedPassword, 'username' => $resetPasswordUsername]);
					
					echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Password reset complete. Please login using your new password.</div>';
					exit();
				}
			}
		} else {
			// One or more mandatory fields are empty. Therefore, display a the error message
			echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Please enter all fields marked with a (*)</div>';
			exit();
		}
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
                Reset password
            </h2>
        <div class="mb-3 mt-3">
            <label for="resetPasswordUsername">Username</label>
            <input type="text" class="form-control" id="resetPasswordUsername" placeholder="Enter resetPasswordUsername" name="resetPasswordUsername"  required>
        </div>
        <div class="mb-3">
            <label for="resetPasswordPassword1">New Password</label>
            <input type="password" class="form-control" id="resetPasswordPassword1" placeholder="Enter password" name="resetPasswordPassword1"  required>
        </div>
        <div class="mb-3">
            <label for="resetPasswordPassword2">Confirm Password</label>
            <input type="password" class="form-control" id="resetPasswordPassword2" placeholder="Confirm  password" name="resetPasswordPassword2"  required>
        </div>
        <button type="submit" class="btn btn-danger" style=>Submit</button>
        <div class="mb-3 mt-3">
            <p> Already have an account? <a href="login.php" style="color:#e8491d;">Login</a>
        </form>
    </div>
</body>
</html>