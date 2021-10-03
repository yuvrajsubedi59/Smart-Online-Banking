<!DOCTYPE html>
<html lang="en">
<head>
  <title>Savings Account</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
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
      <a class="navbar-brand" href="admin_home.php">Online Savings Account</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li class="active"><a href="admin_home.php">Home</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
          <li><a href="logout.php"> Logout </a></li>
      </ul>
	  <ul class="nav navbar-nav navbar-right">
          <li><a href="update-password.php"> Update Password </a></li>
      </ul>
    </div>
  </div>
</nav>

  <div class="wrapper">
  <h2>List of all the users </h2>
	<?php
		// Initialize the session
		session_start();

		// Include config file
		require_once "config.php";

		$result = mysqli_query($link,"SELECT * FROM users");

		echo "<table border='1'>
		<tr>
		<th>User ID</th>
		<th>First Name</th>
		<th>Middle Name</th>
		<th>Last Name</th>
		<th>SSN</th>
		<th>Username</th>
		<th>password</th>
		<th>Created at</th>
		</tr>";

		while($row = mysqli_fetch_array($result))
		{
		echo "<tr>";
		echo "<td>" . $row['id'] . "</td>";
		echo "<td>" . $row['f_name'] . "</td>";
		echo "<td>" . $row['m_name'] . "</td>";
		echo "<td>" . $row['l_name'] . "</td>";
		echo "<td>" . $row['ssn'] . "</td>";
		echo "<td>" . $row['username'] . "</td>";
		echo "<td>" . $row['password'] . "</td>";
		echo "<td>" . $row['created_at'] . "</td>";
		echo "</tr>";
		}
		echo "</table>";
		
		echo "<br>";
		echo"<h2>" . "Users Money Information" . "</h2>"; 
		
		$result2 = mysqli_query($link, "SELECT * FROM account") or die("Error: " . mysqli_error($link));

		echo "<table border='1'>
		<tr>
		<th>User ID</th>
		<th>Account Number</th>
		<th>Deposited Sum</th>
		<th>Total Amount</th>
		<th>Period</th>
		</tr>";

		while($row2 = mysqli_fetch_array($result2))
		{
		echo "<tr>";
		echo "<td>" . $row2['id'] . "</td>";
		echo "<td>" . $row2['account_num'] . "</td>";
		echo "<td>" . $row2['deposit_sum'] . "</td>";
		echo "<td>" . $row2['total_amount'] . "</td>";
		echo "<td>" . $row2['period'] . "</td>";
		echo "</tr>";
		}
		echo "</table>";
		
		echo "<br>";
		echo"<h2>" . "Users who wants to delete their account" . "</h2>"; 
		
		$result1 = mysqli_query($link, "SELECT * FROM delete_user") or die("Error: " . mysqli_error($link));

		echo "<table border='1'>
		<tr>
		<th>User ID</th>
		<th>Delete Message</th>
		</tr>";

		while($row1 = mysqli_fetch_array($result1))
		{
		echo "<tr>";
		echo "<td>" . $row1['id'] . "</td>";
		echo "<td>" . $row1['message'] . "</td>";
		echo "</tr>";
		}
		echo "</table>";
		
		if($_SERVER["REQUEST_METHOD"] == "POST"){
		$id = $_POST["delete_id"];
		$query1 = mysqli_query($link, "DELETE FROM `users` WHERE id = $id") or die("Error: " . mysqli_error($link));
		$query2 = mysqli_query($link, "DELETE FROM `account` WHERE id = $id") or die("Error: " . mysqli_error($link));
		$query3 = mysqli_query($link, "DELETE FROM `delete_user` WHERE id = $id") or die("Error: " . mysqli_error($link));
		//header("location: admin_home.php");
	}
		

		//mysqli_close($con);
?><br>
	<div class="form-group ">
		  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
		  <label>Enter an ID to delete the account</label><br>
          <input type="text" name="delete_id">
		  <input type="submit" name = "submit" class="btn btn-primary" value="Delete">
		  </form>
</div>
</body>
</html>