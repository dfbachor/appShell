<?php
	require("config.php");
    

	// print_r($_POST); exit();
	
	if(empty($_POST['username'])) die("Username required");
	if(empty($_POST['oldPassword'])) die("Old Password required");	
	if(empty($_POST['newPassword'])) die("New Password required");	
		
	$username = $_POST['username'];
	$oldPassword = $_POST['oldPassword'];
	$newPassword = $_POST['newPassword'];
	$oldHash = md5($oldPassword);
	$newHash = md5($newPassword);
	
	$sql = 'update users set 
				rawPassword = :newPassword, 
				hashPassword = :newHashPassword
			where
				username = :username and
				hashPassword = :oldHashPassword';
	
	$query_params = array(
			':newPassword' => $newPassword,
			':newHashPassword' => $newHash,			
			':username' => $username, 
			':newHashPassword' => $newHash,			
			':oldHashPassword' => $oldHash 
			); 				
	
	try {  
            $stmt = $db->prepare($sql); 
            $result = $stmt->execute($query_params); 
			$count = $stmt->rowCount();

			if($count =='0'){
				echo "Your password change failed, please check your old password!";
			}
			else{
				echo "Your password has successfully been changed!";
			}
			
    } catch(PDOException $ex) { 
	        echo "0 .Failed to run change password query: " . $ex->getMessage(); 
			logmsg("updateAccount.php : Failed to run change password query: " . $ex->getMessage());
			exit();
    } 	
	
	
	
	

?>