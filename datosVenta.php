<?php
	
	include_once('inc/header.php');	
	if(isset($_REQUEST['venta']))
	{
		$venta = $_REQUEST['venta'];
	
			
		//obtemos los atributos de esa venta
		try 
		{
			$query = "SELECT IdVenta, FechaVenta, FechaCobro, FormaPago, Observaciones,IdCliente,Antiguedad,Descuento FROM venta WHERE IdVenta = $venta";
			$stmt = $dbh->query($query);	
           
			
			//para cada venta
			if ($stmt->rowCount() == 1)
			{
				$row = $stmt->fetch();
				$fecha=$row['FechaVenta'];
				
                $IdVenta=$row['IdVenta'];
                
                $queryPre="SELECT * FROM presupuesto WHERE IdVenta=$IdVenta";
                $stmtPre = $dbh->query($queryPre);
                
                if ($stmtPre->rowCount() == 1)
				{
                    $ventaPresupuesto = true;
                    $rowPre = $stmtPre->fetch();
                    $presupuesto = $rowPre['IdPresupuesto'];
                }
				else
					$ventaPresupuesto = false;
                
				
				echo '<a href="menuVentas.php"><h4>Menú ventas</h4></a>';
				
				if(isset($_REQUEST['noBorrado'])){
					echo '<p>- La venta no puede ser borrada ya que su factura correspondiente ya ha sido emitida.</p><br />';
				}
				
				echo '<table class="tabla-centrada">';
				
				echo '<table class="tabla-listado">';
			
                if ($ventaPresupuesto)
                	$cadenaAux=($row['IdCliente']==null)?'<a href="seleccionarTipoTicket.php?venta='.$row['IdVenta'].'" target="_new">Ticket</a>/Factura':'Ticket/<a href="seleccionarTipoFactura.php?presupuesto='.$presupuesto.'&facturaPresupuesto=si">Factura</a>';
                else
                	$cadenaAux=($row['IdCliente']==null)?'<a href="seleccionarTipoTicket.php?venta='.$row['IdVenta'].'" target="_new">Ticket</a>/Factura':'Ticket/<a href="seleccionarTipoFactura.php?venta='.$row['IdVenta'].'">Factura</a>';

				//cabecera de la tabla
				echo '<tr class="filaImpar"><td>IdVenta</td><td>Fecha venta</td><td>Fecha cobro</td><td>Forma pago</td>';
				
				if ($row['Descuento'] != 0)	
					echo '<td>Descuento</td>';
					
				echo '<td>Precio total (con IVA)</td><td>Antigüedad</td><td>'.$cadenaAux.'</td></tr>';
				
				echo '<tr class="filaPar">';
				
				echo '<td>'.$row['IdVenta'].'</td>';
				echo '<td>'.$row['FechaVenta'].'</td>';	
				
				if ($row['FechaCobro'] == null)
					echo '<td><a href="cobraVentaPrev.php?venta='.$row['IdVenta'].'&fuente=datos">Cobrar</a></td>';
				else
					echo '<td>'.$row['FechaCobro'].'</td>';
				
				if ($row['FormaPago'] != null)
					echo '<td>'.$row['FormaPago'].'</td>';					
				else
					echo '<td>---</td>';
					
				if ($row['Descuento'] != 0)
					echo '<td>'.$row['Descuento'].' %</td>';


				$cliente = $row['IdCliente'];
				
				
				if ($cliente != null)
					echo '<td>'.formatoDinero(precioVenta($row['IdVenta'])).'</td>';
				else
					echo '<td>'.formatoDinero(redondea(precioVenta($row['IdVenta']))).'</td>';
				
					
				echo '<td>'.$row['Antiguedad'].'</td>';
				
				if ($cliente ==null)
				{
					echo '<td>Ticket</td>';	
					$hayCliente = false;
				}
				else
				{
					echo '<td>Factura</td>';
					$hayCliente = true;
				}
					
				echo '</table>';
				
				if ($row['Observaciones'] != null)
					echo '<p class=parrafoCentrado>Observaciones: '.$row['Observaciones'].'';
								
				if ($hayCliente)
				{
					$queryaux = "SELECT Nombre,Apellidos,Titulo FROM cliente WHERE IdCliente=$cliente";
					$stmtaux = $dbh->query($queryaux);	
					$rowaux = $stmtaux->fetch();
					echo '<p class="parrafoCentrado">Cliente que solicita la factura: <a href="datosCliente.php?cliente='.$cliente.'">'.$rowaux['Apellidos'].' '.$rowaux['Titulo'].' '.$rowaux['Nombre'].'</a>';
				}
			
				//mostramos la lista de la venta en detalle
				echo '<p class="parrafoCentrado">Detalle de la venta:<p>';
				
				$query = "SELECT Referencia, Unidades, Precio, Comentario, IdLineaVenta, Descuento  FROM lineaventa WHERE IdVenta = $venta";
				$stmt = $dbh->query($query);
				
				echo '<table class="tabla-listado"><tr class="cabeceraTabla"><td><b>Referencia</b></td><td><b>Nombre</b></td><td><b>Unidades</b></td><td><b>Precio unidad</b></td>';
				
				//si es una venta por factura, puede que haya descuentos por articulo
				if ($hayCliente)
					echo '<td><b>Descuento</b></td>';
				if ($ventaPresupuesto)
					echo '<td><b>Precio final</b></td><td><b>Comentario</b></td></tr>';
				else
					echo '<td><b>Precio final</b></td><td><b>Comentario</b></td><td></td></tr>';
				
				$i = 1;
				foreach ($stmt as $row)
				{
					if ($i%2==0)
						echo '<tr class="filaPar">';
					else
						echo '<tr class="filaImpar">';
						
					$i++;
					
					$referencia = $row['Referencia'];
					
					$query2 = "SELECT Nombre  FROM articulo WHERE Referencia = '$referencia'";
					$stmt2 = $dbh->query($query2);
					$row2 = $stmt2->fetch();
					
					$unidades = $row['Unidades'];
					$precio = $row['Precio'];
					$nombre = $row2['Nombre'];
                    $comentario=$row['Comentario'];
					$descuento=$row['Descuento'];
					
					
                    if ($comentario==null)
                        $comentario='---';
					
					$precioTotal = ($unidades * $precio * (100 - $descuento)/100) ;

					
					//si es venta por factura
					if ($hayCliente)
					{   
						if ($ventaPresupuesto)
							echo '<td><a href="datosArticulo.php?articulo='.$referencia.'">'.$referencia.'<a/></td><td>'.$nombre.'</td><td>'.$unidades.'</td><td>'.formatoDinero(precioIVASinRedondeo($precio, $fecha)).'</td><td>'.$descuento.' %</td><td>'.formatoDinero(precioIVASinRedondeo($precioTotal,$fecha)).'</td><td>'.$comentario.'</td>';
						else
							echo '<td><a href="datosArticulo.php?articulo='.$referencia.'">'.$referencia.'<a/></td><td>'.$nombre.'</td><td>'.$unidades.'</td><td>'.formatoDinero(precioIVASinRedondeo($precio, $fecha)).'</td><td>'.$descuento.' %</td><td>'.formatoDinero(precioIVASinRedondeo($precioTotal,$fecha)).'</td><td>'.$comentario.'</td><td><a href="eliminaLineaVenta.php?venta='.$venta.'&lineaventa='.$row['IdLineaVenta'].'">Eliminar</a></td>';
					}
					else
					{                       
						echo '<td><a href="datosArticulo.php?articulo='.$referencia.'">'.$referencia.'<a/></td><td>'.$nombre.'</td><td>'.$unidades.'</td><td>'.formatoDinero(precioIVA($precio, $fecha)).'</td><td>'.formatoDinero(precioIVA($precioTotal,$fecha)).'</td><td>'.$comentario.'</td><td><a href="eliminaLineaVenta.php?venta='.$venta.'&lineaventa='.$row['IdLineaVenta'].'">Eliminar</a></td>';
					}
				}
				
				echo '</table>';
									
				if (!$hayCliente)
					echo '<p class="parrafoCentrado"><a href="altaVenta.php?tipo=ticket&venta='.$venta.'&continuar=si"><input name="continuar" type="submit" value="Continuar venta" class="boton" /></a></p>';
				else{
					if (!$ventaPresupuesto)
						echo '<p class="parrafoCentrado"><a href="altaVenta.php?tipo=factura&venta='.$venta.'&continuar=si"><input name="continuar" type="submit" value="Continuar venta" class="boton" /></a></p>';
				}	
				if($stmt->rowcount() == 0)
					echo '<p class="parrafoCentrado"><a href="borrarVenta.php?venta='.$venta.'"><input name="borrarVenta" type="button" value="Borrar venta" class="boton" /></a></p>';
				
				if($ventaPresupuesto)
					echo '<p class="parrafoCentrado"><a href="datosPresupuesto.php?presupuesto='.$presupuesto.'"><input name="presupuestoCorrespondiente" type="button" value="Presupuesto correspondiente" class="boton" /></a></p>';
				
			}
		}
		catch(PDOException $e ) {
			// tratamiento del error
			die("Error PDO: ".$e->GetMessage());
		}
	}

	include_once('inc/footer.php');

?>