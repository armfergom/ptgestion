<?php
	
	include_once('inc/header.php');
	if(isset($_REQUEST['articulo']))
	{
		$articulo = $_REQUEST['articulo'];
	
		//obtemos los atributos de ese artículo
		try 
		{
			$query = "SELECT Referencia, Nombre, Precio, Coste, ReferenciaProveedor, Observaciones, Imagen, IdProveedor, Unidades FROM articulo WHERE Referencia = '$articulo'";
			$stmt = $dbh->query($query);							
			
			//para cada artículo añadimos sus propiedades
			if ($stmt->rowCount() == 1)
			{
				$row = $stmt->fetch();
				
				echo '
				<a href="menuArticulos.php"><h4>Menú artículos</h4></a>
					<table class="tabla-centrada">
					<tr>
					<td><a href="modificarArticulo.php?articulo='.$row['Referencia'].'"><button type="button" class="boton" id="botonModificarArt">Modificar</button></td>
					<td><a href="borrarArticulo.php?articulo='.$row['Referencia'].'"><button type="button" class="boton" id="botonBorrarArt" >Borrar</button></a></td>
					</tr>
					</table>
					<br />
				';
				
				echo '<table class="tabla-listado">';
			
				//cabecera de la tabla
				echo '<tr class="filaImpar"><td>Referencia</td><td>Nombre</td><td>Precio (Con IVA)</td><td>Proveedor</td><td>RefProveedor</td><td>Unidades</td></tr>';
				
				echo '<tr class="filaPar">';
				
				echo '<td>'.$row['Referencia'].'</td>';
				echo '<td>'.$row['Nombre'].'</td>';
				echo '<td>'.formatoDinero(precioIVA($row['Precio'],null)).'</td>';		
				
				//del proveedor queremos su nombre
				$IdProveedor = $row['IdProveedor'];
				if ($IdProveedor == NULL)
					$IdProveedor = 0;
				$query = "SELECT Nombre FROM proveedor WHERE IdProveedor = $IdProveedor";
				$stmt2 = $dbh->query($query);
				
				//si existe el proveedor
				if ($stmt2->rowCount() == 1)
				{		
					$nombreProveedor = $stmt2->fetch();
					echo '<td>'.$nombreProveedor['Nombre'].'</td>';
				}
				else
					echo '<td>---</td>';
				
				if($row['ReferenciaProveedor']!=null)
					echo '<td>'.$row['ReferenciaProveedor'].'</td>';
				else
					echo '<td>---</td>';	
					
				echo '<td>'.$row['Unidades'].'</td>';
				
				echo '</tr>';
				
				echo '</table>';
				
				if ($row['Observaciones'] != null)
					echo '<p class=parrafoCentrado>Observaciones: '.$row['Observaciones'].'';
			
				//mostramos la imagen centrada
				$imagen = getImage($articulo);
				
				if ($imagen != null)
					echo '<br /><div class="centrado"><img class="imagenMediana" src="getImage.php?articulo=' . $articulo .'" /></div>';
				
				echo '<br/><br/><table class="tabla-centrada">';
				
				//imprimimos las compras del articulo
				$queryCompra="SELECT IdCompra,Unidades FROM lineacompra WHERE Referencia = $articulo";
				$stmtCompra = $dbh -> query($queryCompra);	
				foreach ($stmtCompra as $rowCompra){
					echo '<tr><td class="celdaRef"></td><td class="celdaNombre">COMPRA ID-'.$rowCompra['IdCompra'].'</td><td class="celdaPrecio"></td><td class="celdaUni">'.$rowCompra['Unidades'].' Unidades</td></tr>';
				
				}
				
				//imprimimos las ventas del articulo
				$queryVenta="SELECT IdVenta,Unidades FROM lineaventa WHERE Referencia = $articulo";
				$stmtVenta = $dbh -> query($queryVenta);	
				
				foreach ($stmtVenta as $rowVenta){
					$idVenta = $rowVenta['IdVenta'];
					$queryCliente = "SELECT IdCliente FROM venta WHERE IdVenta = $idVenta";
					$stmtCliente = $dbh -> query($queryCliente);
					$cliente = $stmtCliente -> fetch();
					
					if ($cliente['IdCliente'] == null){
					
						$ticket = calculaNumeroTicket($idVenta);
						
						echo '<tr><td class="celdaRef"></td><td class="celdaNombre">TICKET-'.$ticket.'</td><td class="celdaPrecio">VENTA - '.$idVenta.'</td><td class="celdaUni">'.$rowVenta['Unidades'].' Unidad/es</td></tr>';
					}
					else{
						//calculamos la fecha de la venta para mostrarla en el numero de factura
						$queryDatosVenta = "SELECT FechaVenta,Antiguedad FROM venta WHERE IdVenta = $idVenta";
						$stmtDatosVenta = $dbh -> query($queryDatosVenta);
						$rowDatosVenta = $stmtDatosVenta -> fetch();
						$fecha = $rowDatosVenta['FechaVenta'];
						
						//calculamos el numero de factura si es de antiguedad
						if ($rowDatosVenta['Antiguedad'] == 'Si'){
							$numFactura = calculaNumeroFacturaAntiguedad($idVenta, $fecha);
						}
						//calculamos el numero de factura si NO es de antiguedad
						else{
							$numFactura = calculaNumeroFactura($idVenta, $fecha);
						}
							
						//recuperamos el a–o de venta
						$anoVenta=substr($fecha,0,4);
						
						//imprimimos la venta de ese art’culo con su numero de factura
						echo '<tr><td class="celdaRef"></td><td class="celdaNombre">FACTURA-'.$numFactura.'</td><td class="celdaPrecio">VENTA - '.$idVenta.'</td><td class="celdaUni">'.$rowVenta['Unidades'].' Unidad/es</td></tr>';
					}
					
				}
				
				echo '</table>';
			}
		}
		catch(PDOException $e ) {
			// tratamiento del error
			die("Error PDO: ".$e->GetMessage());
		}
	}

	include_once('inc/footer.php');

?>