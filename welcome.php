<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
$con = mysqli_connect('localhost', 'root', '', 'isp_ys96');
$select = mysqli_select_db($con, "isp_ys96");
if(isset($_POST['submit']))
{
	$name = $_FILES['file']['name'];
	$temp = $_FILES['file']['tmp_name'];
	
	move_uploaded_file($temp,"uploaded/".$name);
	$des=$_POST['des'];
	$url = "http://localhost/term_project_css/uploaded/$name";
	$query = mysqli_query($con, "INSERT INTO `videosname` (`name`,`url`,`des`)VALUE ('$name','$url','$des')");
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="css/style.css">
	<script src="https://kit.fontawesome.com/ec0db17b08.js" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link rel="stylesheet" href="css/style.css">
</head>
<body style="background:#0fa">
<section>
	<a href="byuser.php?un=<?php echo  htmlspecialchars($_SESSION['username']);?>" class="user-link">
<div class="user-intro"><i class="far fa-user fa-2x"></i>
<b><?php echo htmlspecialchars($_SESSION["username"]);?></b><br>
</a>
<a href="logout.php" class="btn btn-danger" style="left:50%"> Log out</a>
</div>

	<div class='up-ll'><a href="upload.php" class="btn btn-success">Upload <i class="fas fa-upload"></i></a></div>
	<div class='title'><h1 style='margin-left:20%;'>VSP</h1><p>Video Sharing Platform</p></div>
</section>
<section class="sect-2">
	<hr>
	<?php

$query = mysqli_query($con, "SELECT * FROM `videosname`");
while($row = mysqli_fetch_assoc($query))
{
	#$id = $row['id'];
	#$name = $row['name'];
	
	//echo "<a href=\'watch.php?id=$row['id']\'>$name</a><br />";
	?>
<!--	<a href="watch.php?id=<?php echo $row['id']?>"><?php #echo $row['name']?></a><br>-->
<a href="watch.php?id=<?php echo $row['id']?>">
<div class="card" style="width: 14rem;">
  <img src="thumbnails/<?php echo $row['thumbnail']?>" class="card-img-top" alt="<?php echo $row['name']?>" onerror="this.src='thumbnails/nothumb.png'">
  <div class="card-body">
	  <h3 class="card-title"><?php echo $row['title']?></h3>
    <p class="card-text"><?php echo $row['des']?></p>
  </div>
</div>
</a>
<?php
}
?>
</section>
	
<!--	<form action="welcome.php" method="POST" enctype="multipart/form-data">
	<h2 style="color:white; font-size:30px; font-family:'Comic Sans MS', cursive, sans-serif;"> Upload your videos here: </h2>
	<input class="btn-danger" type="file" name="file" accept="video/*"/>
	<input type="text" placeholder="Description:" name="des">
    <input type="submit" name="submit" value="Upload!" />
	</form>

	<?php

	#if(isset($_POST['submit']))
	#{
	#	echo "<br />".$name." has been uploaded";
	#}

	?>-->
	</body>
</html>