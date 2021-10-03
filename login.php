<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: home.php");
    exit;
}
 
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $password = $username_err = $password_err = "";
//$username_err = $password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
	
	if(isset($_POST['user_login'])){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $db = "SELECT id, username, password FROM Users WHERE username = ?";
        
        if($statement = $link->prepare($db)){
            // Bind variables to the prepared statement as parameters
            $statement->bind_param("s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if($statement->execute()){
                // Store result
                $statement->store_result();
                
                // Check if username exists, if yes then verify password
                if($statement->num_rows == 1){                    
                    // Bind result variables
                    $statement->bind_result($id, $username, $hashed_password);
                    if($statement->fetch()){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            // Redirect user to home page
                            header("location: home.php");
                        } else{
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $username_err = "No account found with that username.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        $statement->close();
    }
    
    // Close connection
    $link->close();
	}
	
	
	
	if(isset($_POST['admin_login'])){
		
		 // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $ad_username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $ad_password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $db = "SELECT admin_id, admin_username, admin_password FROM admin WHERE admin_username = ?";
        
        if($stmt = $link->prepare($db)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_username);
            
            // Set parameters
            $param_username = $ad_username;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Store result
                $stmt->store_result();
                
                // Check if username exists, if yes then verify password
                if($stmt->num_rows == 1){                    
                    // Bind result variables
                    $stmt->bind_result($admin_id, $admin_username, $admin_password);
                    if($stmt->fetch()){
                        if($ad_password == $admin_password){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["admin_loggedin"] = true;
                            $_SESSION["admin_id"] = $id;
                            $_SESSION["admin_username"] = $username;                            
                            
                            // Redirect user to home page
                            header("location: admin_home.php");
                        } else{
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $username_err = "No account found with that username.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        $stmt->close();
    }
    
    // Close connection
    $link->close();
	}
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">-->
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/css/bootstrap.min.css" integrity="sha384-SI27wrMjH3ZZ89r4o+fGIJtnzkAnFs3E4qz9DIYioCQ5l9Rd/7UAa8DHcaL8jkWt" crossorigin="anonymous">

</head>
<body>
  
  <!-- modal for online technical document-->
  <div class="modal fade" id="document">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1>Savings Account</h1>
        </div>
        <div class="modal-body">
          <h2>General information of our system</h2>
          <p>
            Upon visiting our site, the first page that a user will see is a login page. If a user has already made 
            an account, they can login using their registered username and password. If they have not, they will have 
            to go to the sign up page and make an account for them. Registering on our site stores the user’s chosen 
			username in a database along with a hashed version of their password, so that their password is not stolen. 
			Apart from username and password, users are required to enter their first name, middle name, surname, and 
            social security number. Also, we have hashed social security number so that it is not stolen.<br>
            Once logged in, the user is sent to our homepage where they can manage their account. User will have access 
			to view their interest rate, their accumulated interest, their deposited sum, time period of sum deposit, 
			and their profile. <br>
            Users can also withdraw their sum, or transfer it to different account, add sum of money to the account,
			and manage their profile. If wished, users can also withdraw their money and delete their account from our
			system.
          </p>
          <h2>Database Design</h2>
          <p>
            We have a database setup with two different tables for now. The first table is used for user accounts. It stores 
			the usernames, passwords, first name, last name, social security number, and the time account was created 
			for every account that is made on our system. In this table, id is the primary key, ssn and username are unique
			keys, and created_at is current timestamp(). The second table is for users' money information. It stores the 
			information about users' sum of money, their interest rate, money collected so far, and links to other accounts.
			The primary key for this table is user id again and username is the foreign key that links to the first table.
          </p>
          <h2>Safety of the users' information</h2>
          <p>
            Our system has extra layer of security for the users' information. The passwords are hashed before
			storage in our database which protects from users' password from getting stolen or broken. We, admin,
			even won't have access to the users' password which adds protection and confidence to the users. Moreover,
			SSN being confidential part of users' information that we take, SSN is also hashed before storage to give
			users a feeling of safety. 
          </p>
        </div>
      </div>
    </div>
  </div>  

  <!-- modal for online help-->
  <div class="modal fade" id="help">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1>Help Me!!!</h1>
        </div>
        <div class="modal-body">
          <h2>Registering</h2>
          <p>
            To register an account on our website, you must first click on the Register button to go to the registration page. 
            From there, simply fill up your personal information along with desired username and password for your account. 
			If an account already exists with your chosen username, you will need to choose a different username. Passwords 
			must be at least 6 letters long to register an account.
          </p>
          <h2>Logging In</h2>
          <p>
            Simply type in the username and password associated with your account to login. A successful Login will take you to
            the website's homepage, where you can do many things with your account. If the typed username is not found, please
            check that it was not mispelled and try again. If you do not already have an account, you will have to use the registration
            page to make one before you can login.
          </p>
          <h2>Logging Out</h2>
          <p>
            You can log out of your account from any page (other that the login and register pages) by clicking the red log out 
            button. Logging out will take you back to the login page.
          </p>
        </div>
        <div class="modal-footer">
          <input type="button" class="btn btn-primary" data-dismiss="modal" value="Thank You!!!">
        </div>
      </div>
    </div>
  </div>

  <!-- needed for bootstrap modals -->
  <script src="http://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/js/bootstrap.min.js" integrity="sha384-3qaqj0lc6sV/qpzrc1N5DC6i1VRn/HyX4qdPaiEFbn54VjQBEU341pvjz7Dv3n6P" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>

  <!-- login box -->
  <div class="wrapper" width="500">
    <h2 style = "color:white; font-weight: bold;" >Login to your savings account</h2>
    <p style = "color:white; font-weight: bold;">Please fill in your username and password to login.</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="form">
      <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
        <label style = "color:white">Username</label>
        <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
        <span style = "color:#04FC0E" class="help-block"><?php echo $username_err; ?></span>
      </div>    
      <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
        <label style = "color:white">Password</label>
        <input type="password" name="password" class="form-control">
        <span style = "color:#04FC0E" class="help-block"><?php echo $password_err; ?></span>
      </div>
      <div class="form-group">
        <input type="submit" name = "user_login" class="btn btn-primary" value="Login">
		<input type="submit" name = "admin_login" class="btn btn-primary" value="Login as admin">
		
      </div>
      <p id="dont">Don't have an account? <a href="register.php" class="btn btn-primary" role="button" aria-pressed="true">Register</a></p>
    </form>
    <!-- modal buttons -->
    <input type="button" class="btn btn-primary" data-toggle="modal" data-target="#document" value="Learn More">
    <input type="button" class="btn btn-primary" data-toggle="modal" data-target="#help" value="I Need Help!">
  </div>

</body>
</html>