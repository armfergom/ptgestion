<?php

    include_once ('inc/db_connect.php');
	
	$IdPresupuesto = $_REQUEST['presupuesto'];
	$IdLineaPresupuesto = $_REQUEST['lineapresupuesto'];
	
	try
	{
		//borramos la linea presupuesto
		$sql = "DELETE FROM lineapresupuesto WHERE IdPresupuesto = $IdPresupuesto AND IdLineaPresupuesto = $IdLineaPresupuesto";
		$dbh->exec($sql);
	}
	catch(PDOException $e ) 
	{
		// tratamiento del error
		die("Error PDO: ".$e->GetMessage());
	}
	
	header("Location: datosPresupuesto.php?presupuesto=$IdPresupuesto");

?>