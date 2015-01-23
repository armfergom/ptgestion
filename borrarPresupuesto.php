<?php

    include_once ('inc/db_connect.php');
	
	$IdPresupuesto = $_REQUEST['presupuesto'];
	
	try
	{
		$sql = "DELETE FROM presupuesto WHERE IdPresupuesto=$IdPresupuesto";
		$dbh -> exec($sql);
		header("Location: listadoPresupuestos.php");
	}

	catch(PDOException $e ) 
	{
		// tratamiento del error
		die("Error PDO: ".$e->GetMessage());
	}
	


?>