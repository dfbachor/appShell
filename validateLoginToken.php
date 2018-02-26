<?php

	//print_r($_POST); exit();
	
 require("config.php");
    
    // echo $_SERVER['REQUEST_METHOD'];
	// print_r($_POST); exit();
	
	if(empty($_POST['loginToken'])) exit();
	$token = $_POST['loginToken'];
			
    $query = 'SELECT username FROM rememberme WHERE token = :token';
    $query_params = array(':token' => $token);

    try { 
        $stmt = $db->prepare($query); 
        $result = $stmt->execute($query_params); 
    } catch(PDOException $ex){ 
       	echo "0 .Failed to run query: " . $ex->getMessage();
       	exit();
    } 
    
    $row = $stmt->fetch(); 
    
    if($row) { // this is a successful hit
            
			// echo $row['username']; exit();
			
			
			$query = "SELECT ID, username, name FROM users WHERE username = :userName";
			$query_params = array(':userName' => $row['username']);

			try { 
				$stmt = $db->prepare($query); 
				$result = $stmt->execute($query_params); 
				
				$outData = array();
				while($row = $stmt->fetch()) {
					$outData[] = $row;
				} 
				//echo json_encode($outData);
				echo '{"user":' . json_encode($outData) . '}'; 
				exit();
			} catch(PDOException $ex){ 
				echo "0 .Failed to run query: " . $ex->getMessage();
				exit();
			} 

    } else { // failed login
	    echo -1; // no hit on token
	    exit();
    }

?>