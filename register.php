<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
$fname = $mname = $lname = $ssn = "";
$fname_err = $lname_err = $ssn_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    $mname = trim($_POST["middlename"]);
    //validate firstname
    if(empty(trim($_POST["firstname"]))){
        $fname_err = "Please enter your firstname.";
        echo $fname_err;
    } else {
        $fname = trim($_POST["firstname"]);
    }
    //validate lastname
    if(empty(trim($_POST["lastname"]))){
        $fname_err = "Please enter your lastname.";
        echo $lname_err;
    } else {
        $lname = trim($_POST["lastname"]);
    }
    //validate social security number
    if(empty(trim($_POST["ssn"]))){
        $ssn_err = "Please enter your Social security number.";
        echo $ssn_err;
    } else if((strlen(trim($_POST["ssn"]))) > 9){
        $ssn_err = "Social security number must be 9 digits.";
    }
    else {
      // Prepare a select statement
      $sql = "SELECT id FROM users WHERE ssn = ?";

      if($stmt = mysqli_prepare($link, $sql)){
          // Bind variables to the prepared statement as parameters
          mysqli_stmt_bind_param($stmt, "s", $param_ssn);

          // Set parameters
          $param_ssn = trim($_POST["ssn"]);

          // Attempt to execute the prepared statement
          if(mysqli_stmt_execute($stmt)){
              /* store result */
              mysqli_stmt_store_result($stmt);

              if(mysqli_stmt_num_rows($stmt) == 1){
                  $ssn_err = "This SSN is already taken.";
                  echo $ssn_err;
              } else{
                  $ssn = trim($_POST["ssn"]);
              }
          } else{
              echo "Oops! Something went wrong. Please try again later.";
          }
          // Close statement
          mysqli_stmt_close($stmt);
      }
    }
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
        echo $username_err;
    }
    else{
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
    }
    else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }

    // Check input errors before inserting in database
    if(empty($fname_err) && empty($lname_err) && empty($ssn_err) && empty($username_err) && empty($password_err) && empty($confirm_password_err)){

        // Prepare an insert statement
        $sql = "INSERT INTO users (f_name, m_name, l_name, ssn, username, password)
                VALUES (?, ?, ?, ?, ?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssss", $param_f_name, $param_m_name, $param_l_name, $param_ssn, $param_username, $param_password);

            // Set parameters
            $param_f_name = $fname;
            $param_m_name = $mname;
            $param_l_name = $lname;
            $param_ssn = password_hash($ssn, PASSWORD_DEFAULT);
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "COULD NOT INSERT DATA TO THE DATABASE";
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
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <nav class="navbar navbar-inverse">
    <div class="container-fluid">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="home.php">Online Savings Account</a>
      </div>
      <div class="collapse navbar-collapse" id="myNavbar">
        <ul class="nav navbar-nav">
          <li><a href="home.php">Home</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true):?>
            <li><a href="logout.php"> Logout </a></li>
          <?php else: ?>
            <li><a href="login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

    <div style = "top: 64%" class="wrapper">
        <h2 style = "color:white; font-weight:bold; ">Create Your Account</h2>
        <p style = "color:white; font-weight:bold;">Please fill up the form below to create your account&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="form">
            <div class="form-group <?php echo (!empty($fname_err)) ? 'has-error' : ''; ?>">
              <label style = "color: white;">First name</label>
              <input type="text" name="firstname" class="form-control" value="<?php echo $fname; ?>">
              <span style="color:#04FC0E;" class="help-block"><?php echo $fname_err; ?></span>
              <label style = "color: white;">Middle name</label>
              <input type="text" name="middlename" class="form-control" value="<?php echo $mname; ?>">
            </div>
            <div class="form-group <?php echo (!empty($lname_err)) ? 'has-error' : ''; ?>">
              <label style = "color: white;">Surname</label>
              <input type="text" name="lastname" class="form-control" value="<?php echo $lname; ?>">
              <span style="color:#04FC0E;" class="help-block"><?php echo $lname_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($ssn_err)) ? 'has-error' : ''; ?>">
              <label style = "color: white;"> Social Security Number</label>
              <input type="text" name="ssn" class="form-control" value="<?php echo $ssn; ?>">
              <span style="color:#04FC0E;" class="help-block"><?php echo $ssn_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label style = "color: white;">Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span style="color:#04FC0E;" class="help-block"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label style = "color: white;">Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span style="color:#04FC0E;" class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label style = "color: white;">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span style="color:#04FC0E;" class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
            <div class = "login">
            <p id="dont">Already have an account <a href="login.php" class="btn btn-primary" role="button" aria-pressed="true">Log In</a></p>
			</div>
        </form>
    </div>
</body>
</html>