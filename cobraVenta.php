<?php

    include_once ('inc/db_connect.php');
	
	$IdVenta = $_REQUEST['venta'];
	$fuente = $_REQUEST['fuente'];
	$forma= $_REQUEST['forma'];
	try
	{
		$sql = "UPDATE venta SET FechaCobro=curdate(), FormaPago='$forma' WHERE IdVenta=$IdVenta";
		$dbh -> exec($sql);
		if($fuente=='listado')
			header("Location: listadoVentas.php");
		if($fuente=='datos')
			header("Location: datosVenta.php?venta=$IdVenta");
	}
	catch(PDOException $e ) 
	{
		// tratamiento del error
		die("Error PDO: ".$e->GetMessage());
	}
	


?>