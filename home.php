<?php
// Initialize the session
session_start();

// Include config file
require_once "config.php";

$deposit_amount = 1000; //initialize the default deposit amount to be 1000
$period = 1; // init to 1 year duration
$interest_earned = 0.0;
$deposit_amount_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 if(isset($_POST['deposit'])){
  $deposit_amount = trim($_POST["deposit_amount"]);
  $time = trim($_POST["time"]);
  $bank_account = trim($_POST["bank_account"]);
  $bank_name = trim($_POST["bank_name"]);
  $bank_password = trim($_POST["bank_password"]);
  if ($time == "1"){
	$interest = ($deposit_amount * $time * 10) / 100 ;
  } else if ($time > "1" && $time < "10"){
	$interest = ($deposit_amount * $time * 20) / 100 ;
  } else if ($time >= "10" ) {
	$interest = ($sum * $time * 30) / 100;
  }
  $amount = $deposit_amount + $interest;
  $account = rand(1000, 99999) + $_SESSION["id"];
  
  $sql = "INSERT INTO account (id, account_num,	deposit_sum, total_amount, period, bank_account, bank_name, bank_password)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
  
  if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "iiddisss", $param_id, $param_account, $param_deposit, $param_total, $param_period, $param_bankacc, $param_bankname, $param_bankpass);

            // Set parameters
            $param_id = $_SESSION["id"];
			$param_account = $account;
			$param_deposit = $deposit_amount;
			$param_total = $amount;
			$param_period = $time;
			$param_bankacc = $bank_account;
			$param_bankname = $bank_name;
			$param_bankpass = $bank_password;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
				echo "Successfully deposited";
                header("location: home.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
	}
	if(isset($_POST['submit'])){
		$id = $_SESSION["id"];
		$message = $_POST["delete_message"];
		$query = mysqli_query($link, "INSERT INTO `delete_user` (`id`,`message`) VALUE ('$id','$message')");
	}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Savings Account</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <style>
	 .deposit {
		float:right;
	  right: 10px;
	  width: 750px;
	  border: 3px solid #73AD21;
	  padding: 10px;
	  height: 500px;
	}
	.calculate {
		float:left;
	  left: 10px;
	  width: 750px;
	  border: 3px solid #73AD21;
	  padding: 10px;
	  height: 500px;
	}
	table {
	  font-family: arial, sans-serif;
	  border-collapse: collapse;
	  width: 725px;
	}

	td, th {
	  border: 1px solid #dddddd;
	  text-align: left;
	  padding: 8px;
	}

	tr:nth-child(even) {
	  background-color: #dddddd;
	}
	.footer {
		position: relative;
		bottom: 0px;
		width: 100%;
	}
</style>
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
        <li class="active"><a href="home.php">Home</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true):?>
          <li><a href="logout.php"> Logout </a></li>
        <?php else: ?>
          <li><a href="register.php"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
          <li><a href="login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
        <?php endif;?>
      </ul>
	  <ul class="nav navbar-nav navbar-right">
          <li><a href="update-password.php"> Update Password </a></li>
      </ul>
    </div>
  </div>
</nav>

  <div class="wrapper">
    <?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true):?>
	<div class = "calculate">
        <h4>Please fill this form to calculate the interest earned.</h4>
            <div class="form-group">
              <label>Deposit Amount</label><br>
              <input type="text" id="deposit_amount" class="form-group" value="<?php echo $deposit_amount; ?>">
              <span class="help-block"><?php echo $deposit_amount_err; ?></span>
              <div class = "form-group">
                <label for = "period">Save for (select one)</label> <br>
                <select class = "form-group" id = "period" >
                  <option value = "1"> 1 year</option>
                  <option value = "2"> 2 years</option>
                  <option value = "3"> 3 years</option>
                  <option value = "5"> 5 years</option>
                  <option value = "10"> 10 years</option>
                  <option value = "15"> 15 years</option>
                </select>
              </div>
            </div>
            <div class="form-group">
				<button type= "button" onclick="myvariable()" class="btn btn-primary";> Calculate! </button>
                <!--button type = "button" onclick = "myvariable()"> Calculate </button-->
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
          </form>
		  <h4 style = "display:inline-block; margin-right:10px;" >Your interest is: </h2>
		  <h4 style = "display:inline-block;" id = "result"></h2><br>
		  <h4 style = "display:inline-block; margin-right:10px;" > Your total amount for the choosen period is: </h2>
		  <h4 style = "display:inline-block;" id = "amountresult"></h2>
		  
		  <table>
			  <tr>
				<th>Deposit Time Period</th>
				<th>Interest rate</th>
			  </tr>
			  <tr>
				<td>1 year or less</td>
				<td>10%</td>
			  </tr>
			  <tr>
				<td>between 1 and 10 years</td>
				<td>20%</td>
			  </tr>
			  <tr>
				<td>More than 10 years</td>
				<td>30%</td>
			  </tr>
			</table>
		  </div>
		  
		  <div class = "deposit">
		  <h3 >Go for saving. We have best interest rate in the market. You can deposit your money below!</h3>
		  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group ">
              <label>Bank account number</label><br>
              <input type="text" name="bank_account" class="form-group" ><br>
			  <label>Bank name</label><br>
              <input type="text" name="bank_name" class="form-group" ><br>
			  <label>Password</label><br>
              <input type="password" name="bank_password" class="form-group" ><br>
			  <label>Deposit Amount</label><br>
              <input type="text" name="deposit_amount" class="form-group" value="<?php echo $deposit_amount; ?>">
              <span class="help-block"><?php echo $deposit_amount_err; ?></span>
              <div class = "form-group">
                <label for = "period">Save for (select one)</label> <br>
                <select class = "form-group" id = "period" name = "time">
                  <option value = "1"> 1 year</option>
                  <option value = "2"> 2 years</option>
                  <option value = "3"> 3 years</option>
                  <option value = "5"> 5 years</option>
                  <option value = "10"> 10 years</option>
                  <option value = "15"> 15 years</option>
                </select>
              </div>
            </div>
            <div class="form-group">
				<input type="submit" name ="deposit" class="btn btn-primary" value="Deposit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
          </form>
		  </div>
		  <div class="form-group ">
		  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
		  <br><br><label>If you want to delete your account, leave a message here with a reason.</label><br>
          <input type="text" name="delete_message" style = "width:750px; height:50px;" ><br>
		  <input type="submit" name = "submit" class="btn btn-primary" value="Submit">
		  </form>
		  </div>
		  <script>
		  function myvariable(){
			  var interest;
			  var amount;
		  sum = document.getElementById("deposit_amount").value;
		  time = document.getElementById("period").value;
			if (time == 1){
				interest = sum * time * 0.1;
			} else if (time > 1 && time < 10){
				interest = sum * time * 0.2;
			} else if (time >= 10 ) {
				interest = sum * time * 0.3;
			}
			amount = parseInt(sum) + parseInt(interest);
			document.getElementById("result").innerHTML = interest;
			document.getElementById("amountresult").innerHTML = amount; 
		  }
		  </script>

  <?php else: ?>
    <h3>Collapsible Navbar</h3>
    <p>In this example, the navigation bar is hidden on small screens and replaced by a button in the top right corner (try to re-size this window).
    <p>Only when the button is clicked, the navigation bar will be displayed.</p>
  <?php endif;?>
  </div>
</body>
</html>