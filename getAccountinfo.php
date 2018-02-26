<?php

	require("config.php");
	// print_r($_POST); exit();

	
	if(empty($_POST['userName'])) die("Username required");
	$userName = $_POST['userName'];
	
    $query = "SELECT ID, username, firstname, lastname FROM users WHERE username = :userName";
    $query_params = array(':userName' => $userName);

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
?>