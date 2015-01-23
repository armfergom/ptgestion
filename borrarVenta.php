<?php

    include_once ('inc/db_connect.php');
	
	$IdVenta = $_REQUEST['venta'];
	
	try
	{
		$sql = "DELETE FROM venta WHERE IdVenta=$IdVenta";
		
		$queryVenta = "SELECT * FROM  venta WHERE IdVenta=$IdVenta";
		$stmtVenta = $dbh -> query($queryVenta);
		$rowVenta = $stmtVenta -> fetch();
		$ant = $rowVenta['Antiguedad'];
		
		if ($ant == 'Si'){
			$queryFac = "SELECT * FROM  facturaant WHERE IdVenta=$IdVenta";
		}
		else{
			$queryFac = "SELECT * FROM  factura WHERE IdVenta=$IdVenta";
		}
		
		$stmtFac = $dbh -> query($queryFac);
		
		if ($stmtFac->rowCount() == 1){
			header("Location: datosVenta.php?venta=$IdVenta&noBorrado=Si");
		}
		else{
			$dbh -> exec($sql);
			header("Location: listadoVentas.php");
		}
	}

	catch(PDOException $e) 
	{
		// tratamiento del error
		die("Error PDO: ".$e->GetMessage());
	}
	


?>