<?php

	echo '<head><link rel="stylesheet" type="text/css" href="css/style2.css" /></head><body>';

	include_once ('inc/db_connect.php');
	include_once ('inc/misc.php');
	
	//mostramos las etiquetas
	if (isset($_REQUEST['becara']))
	{	
		$totalProductos = 0;
		
		echo '<div class="dinA4Inv"><table><tr><td class="celdaRef"><b>Referencia</b></td><td class="celdaNombre"><b>Nombre</b></td><td class="celdaPrecio"><b>Precio (con IVA)</b></td><td class="celdaUni"><b>Unidades</b></td></tr>';
		if($_REQUEST['becara']=='si'){
			echo '<h3>Inventario Becara:</h3>';
			$query="SELECT Referencia,Nombre,Precio,Unidades FROM articulo WHERE IdProveedor=1 ORDER BY Referencia ASC";
			$stmt = $dbh -> query($query);	
		}
		else{
			echo '<h3>Inventario otros proveedores:</h3>';
			$query="SELECT Referencia,Nombre,Precio,Unidades FROM articulo WHERE IdProveedor is NULL or IdProveedor <> 1 ORDER BY Referencia ASC";
			$stmt = $dbh -> query($query);	
		}
		
		foreach ($stmt as $row){
            if (!esIntangible($row['Referencia'])){
                echo '<tr><td class="celdaRef">'.$row['Referencia'].'</td><td class="celdaNombre">'.$row['Nombre'].'</td><td class="celdaPrecio">'.precioIVA($row['Precio'],null).'€</td><td class="celdaUni">'.$row['Unidades'].'</td></tr>';
				$totalProductos = $totalProductos + (precioIVA($row['Precio'],null)*$row['Unidades']);
			}
		}
		echo '<tr><td>TOTAL: </td><td>'.formatoDinero($totalProductos).' €</td></tr>';
		echo '</table></div>';
	} 

	disconnect($dbh);
	
?>