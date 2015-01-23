<?php

    include_once ('inc/db_connect.php');
	
	$IdListaBoda = $_REQUEST['listaboda'];
	
	try
	{
		$sql = "DELETE FROM listaboda WHERE IdListaBoda=$IdListaBoda";
		$dbh -> exec($sql);
		header("Location: listadoListasBoda.php");
	}

	catch(PDOException $e ) 
	{
		// tratamiento del error
		die("Error PDO: ".$e->GetMessage());
	}
	


?>