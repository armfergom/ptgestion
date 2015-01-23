<?php
	
	include_once('inc/header.php');			
	//obtemos las compras con todos sus atributos
	try 
	{
		$query = "SELECT IdCompra, Fecha, Observaciones FROM compra ORDER BY IdCompra DESC";
		$stmt = $dbh->query($query);
		
		echo '<a href="menuCompras.php"><h4>Menú compras</h4></a><h4>Listado de compras</h4><table class="tabla-listado">';
		
		//cabecera de la tabla
		echo '<tr class="cabeceraTabla"><td><b>Id</b></td><td><b>Fecha</b></td><td><b>Coste total</b></td><td><b>Proveedor</b></td></tr>';
		
		
		$i=1;
		//para cada compra
		foreach ($stmt as $row)
		{
			$IdCompra = $row['IdCompra'];
			
			$query3="SELECT Referencia FROM lineacompra WHERE IdCompra=$IdCompra";
			$stmt3 = $dbh->query($query3);
			$row3= $stmt3->fetch();
			
			$ref=$row3['Referencia'];
			$query4="SELECT IdProveedor FROM articulo WHERE Referencia='$ref'";
			$stmt4 = $dbh->query($query4);
			$row4= $stmt4->fetch();
			
			$proveedor=$row4['IdProveedor'];
			
			if ($proveedor != null)
			{
				$query2 = "SELECT Nombre FROM proveedor WHERE IdProveedor=$proveedor";
				$stmt2 = $dbh->query($query2);
				$row2= $stmt2->fetch();
				$nombreProv=$row2['Nombre'];
			}
			else
				$nombreProv = '';
			
			if ($i%2==0)
				echo '<tr class="filaPar">';
			else
				echo '<tr class="filaImpar">';
				
			$i++;
			
			
			
			echo '<td><a href="datosCompra.php?compra='.$IdCompra.'">'.$IdCompra.'</a></td>';
			echo '<td>'.$row['Fecha'].'</td>';

			$coste = costeCompra($IdCompra);
			echo '<td>'.formatoDinero($coste).'</td>';	

			echo '<td><a href="datosProveedor.php?idProveedor='.$proveedor.'">'.$nombreProv.'</td>';
			
			echo '</tr>';
		}
		
		echo '</table>';
	}
	catch(PDOException $e ) {
		// tratamiento del error
		die("Error PDO: ".$e->GetMessage());
	}

	include_once('inc/footer.php');

?>