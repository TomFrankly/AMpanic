<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="keywords" content="email scheduling" />
<meta name="description" content="Schedule an email." />
<title>AMpanic - schedule an email</title>
<link rel="stylesheet" type="text/css" href="css/style.css" />
<link href='http://fonts.googleapis.com/css?family=Racing+Sans+One' rel='stylesheet' type='text/css'>
<link rel="shortcut icon" href="img/favicon.png" type="image/x-icon" />
</head>

<body>

	<div id="container" class="index">
	
		<h1 class="title">AMpanic</h1>
		<a href="index.php"><span class="sub">A Simple Email Scheduler</span></a>
		<a href="index.php" class="nav">Home</a>
		
<?php

// starts the register script once the user hits 'Submit'
$submit = $_POST['submit'];

if ($submit) 
{
	// connect to the database
	require_once "includes/connect.php";
	
	// declare variables and match them to form data
	$username = mysql_real_escape_string(trim(strip_tags($_POST['username']))); 
	
	$password = mysql_real_escape_string(trim(strip_tags($_POST['password'])));
	$repeatPassword = mysql_real_escape_string(trim(strip_tags($_POST['repeatpassword'])));
	
	date_default_timezone_set('America/Chicago');
	$date = date("Y-m-d");
	
	$email = mysql_real_escape_string(trim(strip_tags($_POST['email'])));
	
	// run a query to get any entries already in the database with the same username
	$nameCheck = mysql_query("SELECT username FROM users WHERE username='$username'");
	$count = mysql_num_rows($nameCheck);
	
	if ($count != 0)
	{
		// make sure the username isn't already taken
		die("Username already taken! <a href=\"index.php\">Return to home.</a>");	
	}
	
	// do the same for the email - checked below
	$nameCheck2 = mysql_query("SELECT email FROM users WHERE email='$email'");
	$count2 = mysql_num_rows($nameCheck2);

	// check for existence of all fields
	if ($username&&$password&&$repeatPassword)
	{
		
		// make sure the passwords are the same
		if ($password==$repeatPassword) 
		{
			
			// check char length of username and fullname
			if (strlen($username)>25 || strlen($fullName)>25)
			{
				echo "Length of username or full name is too long! <a href=\"index.php\">Return to home.</a>";
			}
			else
			{
				// check password length
				if (strlen($password)>25 || strlen($password)<6)
				{	
					echo "Password must be between 6 and 25 characters. <a href=\"index.php\">Return to home.</a>";
				}
				else
				{
					
					// check that the email is valid
					if (filter_var($email, FILTER_VALIDATE_EMAIL))
					{

						// check that it's not already in use
						if ($count2 == 0)
						{
							
							// generate random number for activation process
							$random = sha1(rand(2345678,98765432));
							
							// encrypt the password using a salt
							$salt = hash('sha512', $random);
							$password = $password.$salt;
							$password = hash('sha512', $password);
							
							// insert all information into the database to register the user (they won't have an active account yet)
							$queryReg = mysql_query("
							
							INSERT INTO users VALUES ('','$username','$password','$email','$date','$random','0')") or die("Couldn't insert data.");

							// store the ID in a variable which will be put in the activation URL
							$lastId = mysql_insert_id();
							
							// send activation email
							$to = $email;
							$subject = "Activate your account on AMpanic";
							$headers = "From: admin@thomasjfrank.com";
							$server = "174.120.31.190";

// this creates the email
// tab structure is messed up here because the whitespace would make the email look really bad
$body = "
							
Hello $fullName,\n\n
							
You need to active your account on AMpabnic before you can access the student-only portion of the site. You can activate your account by clicking the link below:
http://www.thomasjfrank.com/AMpanic2/execution/activate.php?id=$lastId&code=$random \n\n
							
Have a great day!
							
";
							
							//function to send mail
							mail($to, $subject, $body, $headers);
							
							
							?>
							<p class="notify">You have been registered! Before you can log in, you need to activate your account through the link that was just sent to the email you registered with. <a href="index.php">Return to home.</a></p>

							<?php
							
							$fullName = "";
							$username = "";
						}
						else
						{
							?>
							<p class="notify">That email is already taken! Please use another one.</p>
				  					  <?php	
						}
					}
					else
					{
						?>
							<p class="notify">Please enter a valid email address.</p>
				  					  <?php		
					}
					
				}
				
			}
			
		}
		else
		{
			?>
				<p class="notify">Your passwords do not match.</p>
			<?php
		}
	}
	else 
	{
		?>
			<p class="notify">Please fill in <strong>all</strong> fields!</p>
  		<?php
	}
	
}

?>
                
                <p>Register for an account below!</p>
                

                
                <form action="register.php" method="post">
                    <table>
                        <tr>
                            <td>
                            Choose a username:
                            </td>
                            <td>
                            <input type="text" name="username" value="<?php echo $username ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            Choose a password:
                            </td>
                            <td>
                            <input type="password" name="password" />
                            </td>
                        </tr>
                        <tr>
                            <td>
                            Repeat your password:
                            </td>
                            <td>
                            <input type="password" name="repeatpassword" />
                            </td>
                        </tr>
                        <tr>
                            <td>
                            Email address:
                            </td>
                            <td>
                            <input type="text" name="email" />
                            </td>
                        </tr>
                    </table>
                    <p></p>
                    <input type="submit" name="submit" value="Register" />
                    
            <div class="bottomlinks">
				<a href="about.php">About</a>
				<a href="faq.php">FAQ</a>
				<a href="contact.php">Contact</a>
			</div>

	</div>
	
</body>

</html>