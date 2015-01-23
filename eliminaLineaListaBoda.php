<?php

    include_once ('inc/db_connect.php');
	
	$IdListaBoda = $_REQUEST['listaboda'];
	$IdLineaListaBoda = $_REQUEST['linealistaboda'];
	
	try
	{
		//borramos la linea lista boda
		$sql = "DELETE FROM linealistaboda WHERE IdListaBoda = $IdListaBoda AND IdLineaListaBoda = $IdLineaListaBoda";
		$dbh->exec($sql);
	}
	catch(PDOException $e ) 
	{
		// tratamiento del error
		die("Error PDO: ".$e->GetMessage());
	}
	
	header("Location: datosListaBoda.php?listaboda=$IdListaBoda");

?>