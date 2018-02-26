<?php
	require("config.php");
	
	//print_r($_POST); exit();
	
	if(empty($_POST['token'])) exit();
	if(empty($_POST['password'])) exit();
	
	$token = $_POST['token'];
	$password = $_POST['password'];
	$hash = md5($password);
	
	$query = "SELECT userName FROM resetToken WHERE token = :token" ;
    $query_params = array(':token' => $token);

    try { 
        $stmt = $db->prepare($query); 
        $result = $stmt->execute($query_params); 
    } catch(PDOException $ex){ 
       	echo "0 .Failed to run query: " . $ex->getMessage();
		logmsg("resetpw.php : Failed to run query: " . $ex->getMessage());
       	exit();
    } 
	
    $row = $stmt->fetch(); 
    if($row) { 
	    //echo $row['userName']; exit();
	    $username = $row['userName'];
	    
        // this is a successfull hit on the token
        $sql = 'update users set 
						rawPassword = :password, 
						hashPassword = :hash
					where 
						username = :username';	

		$query_params = array(
			':username' => $username, 
			':password' => $password, 
			':hash' => $hash); 	
	
		
		//print_r($query_params); exit();
		
		try {  
            $stmt = $db->prepare($sql); 
            $result = $stmt->execute($query_params); 
	    } catch(PDOException $ex) { 
	        echo "0 .Failed to run query: " . $ex->getMessage(); 
			logmsg("resetPW .php : Failed to run update query: " . $ex->getMessage());
			exit();
	    } 	
	    
	    echo true;
	    exit();
    } else {
	    echo "0 .Cannot find reset token for your request.";
	    exit();
    }
		
?>
