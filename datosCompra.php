<?php
	
	include_once('inc/header.php');	
	if(isset($_REQUEST['compra']))
	{
		$compra = $_REQUEST['compra'];
	
		//obtemos los atributos de esa compra
		try 
		{
			$query = "SELECT IdCompra, Fecha, Observaciones FROM compra WHERE IdCompra = $compra";
			$stmt = $dbh->query($query);							
			
			$enlace = 'distribuirEtiquetas.php?generar=1';
			
			//para esta compra
			if ($stmt->rowCount() == 1)
			{
				$row = $stmt->fetch();
				
				echo '
					<a href="menuCompras.php"><h4>Menú compras</h4></a>
					<table class="tabla-centrada">
				';
				
				echo '<table class="tabla-listado">';
			
				//cabecera de la tabla
				echo '<tr class="filaImpar"><td>IdCompra</td><td>Fecha</td><td>Coste total</td><td>Proveedor</td></tr>';
				
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
					$query2 = "SELECT Nombre FROM proveedor WHERE IdProveedor=$proveedor";
					$stmt2 = $dbh->query($query2);
					$row2= $stmt2->fetch();
					$nombreProv=$row2['Nombre'];
				}
				else
				{
					$nombreProv = '';
					$proveedor = 'null';
				}
					
				echo '<tr class="filaPar">';
				
				echo '<td>'.$row['IdCompra'].'</td>';
				echo '<td>'.$row['Fecha'].'</td>';			
				echo '<td>'.formatoDinero(costeCompra($row['IdCompra'])).'</td>';	
				echo '<td><a href="datosProveedor.php?idProveedor='.$proveedor.'">'.$nombreProv.'</td>';
				echo '</table>';
				
				if ($row['Observaciones'] != null)
					echo '<p class=parrafoCentrado>Observaciones: '.$row['Observaciones'].'';
			
				echo '<p class="parrafoCentrado"><a href="imprimirCompra.php?compra='.$compra.'" target="_new"><input name="imprimirCompra" type="button" value="Imprimir compra" class="boton" /></a></p>';

				//mostramos la lista de la compra en detalle
				echo '<p class="parrafoCentrado">Detalle de la compra:<p>';
				
				$query = "SELECT Referencia, Unidades, Coste FROM lineacompra WHERE IdCompra = $compra";
				$stmt = $dbh->query($query);
			
				echo '<table class="tabla-listado"><tr class="cabeceraTabla"><td><b>Referencia</b></td><td><b>Unidades</b></td><td><b>Coste unidad</b></td><td><b>Coste total</b></td><td></td></tr>';
				
				$i = 1;
				foreach ($stmt as $row)
				{
					if ($i%2==0)
						echo '<tr class="filaPar">';
					else
						echo '<tr class="filaImpar">';											
					
					$referencia = $row['Referencia'];
					$unidades = $row['Unidades'];
					$coste = formatoDinero($row['Coste']);	
					$costeTotal = formatoDinero($unidades * $coste);
					
					$enlace .= "&referencia$i=$referencia&etiquetas$i=$unidades";
					
					$i++;
					
					echo '<td><a href="datosArticulo.php?articulo='.$referencia.'">'.$referencia.'</a></td><td>'.$unidades.'</td><td>'.$coste.'</td><td>'.$costeTotal.'</td><td><a href="eliminaLineaCompra.php?compra='.$compra.'&articulo='.$referencia.'">Eliminar</a></td>';
				}
				
				echo '</table>';
				
				echo '<p class="parrafoCentrado"><a href="'.$enlace.'" target="_new"><input name="generar" type="submit" value="Generar etiquetas" class="boton" /></a><a href="altaCompra.php?compra='.$compra.'&continuar=si&proveedor='.$proveedor.'"><input name="continuar" type="submit" value="Continuar compra" class="boton" /></a></p>';
			}
		}
		catch(PDOException $e ) {
			// tratamiento del error
			die("Error PDO: ".$e->GetMessage());
		}
	}

	include_once('inc/footer.php');

?>