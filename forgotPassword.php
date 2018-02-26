<?php
	
 require("config.php");

	if(empty($_POST['username'])) exit();
	
	$username = $_POST['username'];
	$email = $_POST['username'];

	$email = filter_var($email, FILTER_SANITIZE_EMAIL);

	// Validate e-mail 	//get the user email info with the username or the email
	if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
	    // $email is a valid email address");
	    $query = "SELECT email FROM users WHERE email = :email";
	    $query_params = array(':email' => $email);
	} else {
	    // $email is not a valid email address");
		$query = "SELECT email FROM users WHERE username = :username";
		$query_params = array(':username' => $username);
	}	
	
	try { 
        $stmt = $db->prepare($query); 
        $result = $stmt->execute($query_params); 
    } catch(PDOException $ex){ 
       	echo "0 .Failed to run query: " . $ex->getMessage();
       	exit();
    } 
    
    $row = $stmt->fetch(); 
    
    if($row) { // this is a successful find in the database
		// the message
		$msg = "Hi, <br />Please click the link below to reset your password as requested.";
		$msg .= "<br /><br />";
		
		
		
/*
	unfortunatly we cannot count on the email client to process an html form - it does not send the post array
	
		$msg .= "<form action='http://ncc.dev/applicationShell/resetpw.php' method='POST'>";
			$msg .=	"<input type='text' name='resettoken' value='thevalues'/>";
			
			$msg .= "<input type='submit' value='Reset Password'";
				$msg .= "style='background-color: Transparent; text-decoration: underline; color: blue; cursor: pointer; border: 0'";
			$msg .= "/>";
			
		$msg .=	"</form>";
*/
		$resetToken = generateRandomToken(25);
		
		// insert the token into the database with the username
		$sql = 'insert into resetToken (token, username, tokenDate) values (:loginToken, :username, now())';	
		
			$query_params = array(
				':loginToken' => $resetToken, 
				':username' => $username); 		

			try {  
				$stmt = $db->prepare($sql); 
				$result = $stmt->execute($query_params); 
			} catch(PDOException $ex) { 
				echo "0 .forgotPassword.php : Failed to run query for reset token: " . $ex->getMessage();
				logmsg("forgotPassword.php : Failed to run query for reset token: " . $ex->getMessage());	
				exit();
			} 							
		
		$msg .= "<p><a href='http://ncc.dev/applicationshell/index.php?resetToken=$resetToken'>Reset Password</a></p>";
		
		$msg .= "<br /><br />";
		$msg .= "Thank you";
		

		// use wordwrap() if lines are longer than 70 characters
		$msg = wordwrap($msg,70);
		
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		
		$headers .= 'From: AppShell Support <support@appShell.com>';
		
		// send email
		mail("davidfbachor@gmail.com","Requested from ApplicationShell.com",$msg, $headers);
		echo true;
		exit();
	} else { // no row found with this use info
	    echo -1;
	    exit();
    }
	
	

	
?>