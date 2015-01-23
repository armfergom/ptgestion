<?php

	echo '<head><link rel="stylesheet" type="text/css" href="css/style2.css" /></head><body>';

	include_once ('inc/db_connect.php');
	include_once ('inc/misc.php');
	
	//mostramos las etiquetas
	if (isset($_REQUEST['compra']))
	{	
		$compra=$_REQUEST['compra'];
		$query0="SELECT * FROM compra WHERE IdCompra=$compra";
		$stmt0 = $dbh -> query($query0);
		$row0 = $stmt0 -> fetch();
		$fecha=$row0['Fecha'];
		
		$query3="SELECT Referencia FROM lineacompra WHERE IdCompra=$compra";
		$stmt3 = $dbh->query($query3);
		$row3= $stmt3->fetch();
				
		$ref=$row3['Referencia'];
		$query4="SELECT IdProveedor FROM articulo WHERE Referencia='$ref'";
		$stmt4 = $dbh->query($query4);
		$row4= $stmt4->fetch();
				
		$proveedor=$row4['IdProveedor'];
		
		if ($proveedor != null)
		{
			$query5 = "SELECT Nombre FROM proveedor WHERE IdProveedor=$proveedor";
			$stmt5 = $dbh->query($query5);
			$row5= $stmt5->fetch();
			$nombreProv=$row5['Nombre'];
			$cad='Compra <b>número</b> '.$compra.' con fecha <b>'.$fecha.'</b> y con coste <b>'.formatoDinero(costeCompra($compra)).'</b> del proveedor <b>'.$nombreProv.'</b>.<br /><br />';
		}
		else
		{
			$nombreProv = '';
			$cad='Compra <b>número</b> '.$compra.' con fecha <b>'.$fecha.'</b> y con coste <b>'.formatoDinero(costeCompra($compra)).'</b>. Compra <b>sin proveedor</b>.<br /><br />';
		}
			
		echo '<div class="dinA4Inv">
		'.$cad.'
		<table><tr><td class="celdaRef"><b>Referencia</b></td><td class="celdaNombreCom"><b>Nombre</b></td><td class="celdaCoste1"><b>Coste Ud.</b></td><td class="celdaCoste2"><b>Coste total</b></td><td class="celdaUni"><b>Unidades</b></td></tr>';
		
		$query="SELECT * FROM lineacompra WHERE IdCompra=$compra";
		$stmt = $dbh -> query($query);
		
		foreach ($stmt as $row){
			$ref=$row['Referencia'];
			$query2="SELECT Nombre FROM articulo WHERE Referencia='$ref'";
			$stmt2 = $dbh -> query($query2);
			$row2 = $stmt2 -> fetch();
			$nombre=$row2['Nombre'];
            echo '<tr><td class="celdaRef">'.$ref.'</td><td class="celdaNombre">'.$nombre.'</td><td class="celdaCoste1">'.formatoDinero($row['Coste']).'€</td><td class="celdaCoste2">'.formatoDinero($row['Coste']*$row['Unidades']).'€</td><td class="celdaUni">'.$row['Unidades'].'</td></tr>';
		}
		echo '</table></div>';
	} 

	disconnect($dbh);
	
?>