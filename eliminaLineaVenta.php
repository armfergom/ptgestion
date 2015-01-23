<?php

    include_once ('inc/db_connect.php');
	include_once ('inc/misc.php');
	
	$IdVenta = $_REQUEST['venta'];
	$IdLineaVenta = $_REQUEST['lineaventa'];
	
	try
	{
		eliminaLineaVenta($IdLineaVenta);
	}
	catch(PDOException $e ) 
	{
		// tratamiento del error
		die("Error PDO: ".$e->GetMessage());
	}
	
	header("Location: datosVenta.php?venta=$IdVenta");

?>