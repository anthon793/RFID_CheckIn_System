<?php
//login.php

include('connection.php');



$message = '';

if(isset($_POST["login"]))
{
    $email= $_POST["email"];
	$statement = $conn->prepare("SELECT id, username, email, password FROM users WHERE email = ? LIMIT 1");
	$statement->bind_param("s", $email);
	$statement->execute();
	$result = $statement->get_result();
	
	
	if($result->num_rows > 0)
	{
		
		while($row = $result->fetch_assoc())
		{
            $email= $row["email"];
            $password= $row["password"];
            $id= $row["id"];
			
				if(password_verify($_POST["user_password"], $password))
				{
				
					
					$_SESSION['id'] = $row['id'];
					$_SESSION['username'] = $row['username'];
					header("location:index.php");
					exit();
				}
				else
				{
					header("location:login.php");
					exit();
					// $message = "<label>Wrong Password</label>";
				}
			
			
		}
	}
	else
	{
		header("location:login.php");
		exit();
	}

	$statement->close();
}

?>
