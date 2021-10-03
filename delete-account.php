<?php
// Initialize the session
session_start();

// Include config file
require_once "config.php";
		
  $result = mysqli_query($link, "SELECT id FROM delete_user") or die("Error: " . mysqli_error($link));		
  $sql = "DELETE FROM users WHERE id = ?";
  while($row = mysqli_fetch_array($result)){
  if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);

            // Set parameters
            $param_id = $row['id'];
			

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Account deleted successfully. Destroy the session, and redirect to login page
                //session_destroy();
                //header("location: login.php");
                //exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
  }		
?>