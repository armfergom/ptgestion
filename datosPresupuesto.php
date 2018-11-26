<?php
	
	include_once('inc/header.php');	
	if(isset($_REQUEST['presupuesto']))
	{
		$presupuesto = $_REQUEST['presupuesto'];
	
		//obtemos los atributos de ese presupuesto
		try 
		{
			$query = "SELECT IdPresupuesto,Fecha,Observaciones,IdCliente,IdVenta FROM presupuesto WHERE IdPresupuesto = $presupuesto";
			$stmt = $dbh->query($query);							
			
			//para cada presupuesto
			if ($stmt->rowCount() == 1)
			{
				$row = $stmt->fetch();
				$fecha = $row ['Fecha'];
				
				echo '
					<a href="menuPresupuestos.php"><h4>Menú presupuestos</h4></a>
					<table class="tabla-centrada">
				';
				
				echo '<table class="tabla-listado">';
			
				//cabecera de la tabla
				echo '<tr class="filaImpar"><td>IdPresupuesto</td><td>Fecha presupuesto</td><td>Cliente</td><td>Precio total (con IVA)</td></tr>';
				
				echo '<tr class="filaPar">';
				
				echo '<td>'.$row['IdPresupuesto'].'</td>';
				echo '<td>'.$row['Fecha'].'</td>';	
				
                $IdVenta=$row['IdVenta'];
				$cliente=$row['IdCliente'];
				$queryaux="SELECT Nombre,Apellidos,Titulo FROM cliente WHERE IdCliente=$cliente";
				$stmtaux = $dbh->query($queryaux);	
				$rowaux = $stmtaux->fetch();
				$cliente = $rowaux['Apellidos'].' '.$rowaux['Titulo'].' '.$rowaux['Nombre'];
				
				echo '<td><a href="datosCliente.php?cliente='.$row['IdCliente'].'">'.$cliente.'</td>';	

				echo '<td>'.precioPresupuesto($row['IdPresupuesto']).'</td>';
					
				echo '</table>';
                
				echo '<p class="parrafoCentrado"><a href="imprimirPresupuesto.php?presupuesto='.$presupuesto.'&electronica=no" target="_new"><input name="imprimirPresupuesto" type="button" value="Imprimir presupuesto" class="boton" /></a></p>';

				echo '<p class="parrafoCentrado"><a href="imprimirPresupuesto.php?presupuesto='.$presupuesto.'&electronica=si" target="_new"><input name="imprimirPresupuesto" type="button" value="Imprimir presupuesto electrónico" class="boton" /></a></p>';


				if ($row['Observaciones'] != null)
					echo '<p class=parrafoCentrado>Observaciones: '.$row['Observaciones'].'';
				
				
				//mostramos la lista del presupuesto en detalle
				echo '<p class="parrafoCentrado">Detalle del presupuesto:<p>';
				
				$query = "SELECT Referencia, Unidades, Precio, Descuento, Comentario, IdCapitulo, IdSubcapitulo, IdLineaPresupuesto  FROM lineapresupuesto WHERE IdPresupuesto = $presupuesto ORDER BY IdCapitulo ASC, IdSubcapitulo ASC";
				$stmt = $dbh->query($query);
				
				echo '<table class="tabla-listado"><tr class="cabeceraTabla"><td><b>Referencia</b></td><td><b>Nombre</b></td><td><b>Unidades</b></td><td><b>Precio u.(IVA)</b></td><td><b>Descuento</b></td><td><b>Precio t.(IVA)</b></td><td><b>Comentario</b></td><td><b>Capítulo</b></td><td><b>Subcapítulo</b></td><td></td></tr>';
				
				$i = 1;
				foreach ($stmt as $row2)
				{
					if ($i%2==0)
						echo '<tr class="filaPar">';
					else
						echo '<tr class="filaImpar">';
						
					$i++;
					
					$referencia = $row2['Referencia'];
					$descuento = $row2['Descuento'];
					
					$query2 = "SELECT Nombre FROM articulo WHERE Referencia = '$referencia'";
					$stmt2 = $dbh->query($query2);
					$row3 = $stmt2->fetch();
					
					$unidades = $row2['Unidades'];
					$precio = $row2['Precio'];
					$nombre = substr($row3['Nombre'],0,10);
					$capitulo = $row2['IdCapitulo'];
					
					$query2 = "SELECT Nombre FROM capitulo WHERE IdCapitulo = $capitulo";
					$stmt2 = $dbh->query($query2);
					$row3 = $stmt2->fetch();
					$capitulo = $row3['Nombre'];
					if ($capitulo == null)
						$capitulo = '---';
					
					$subcapitulo = $row2['IdSubcapitulo'];
					
					$query2 = "SELECT Nombre FROM subcapitulo WHERE IdSubcapitulo = $subcapitulo";
					$stmt2 = $dbh->query($query2);
					$row3 = $stmt2->fetch();
					$subcapitulo = $row3['Nombre'];
					if ($subcapitulo == null)
						$subcapitulo = '---';
                        
                    $comentario= $row2['Comentario'];  
					if ($comentario == null)
                        $comentario='---';
                    
                    
					$precioTotal = $unidades * precioIVASinRedondeo($precio,$fecha);
					
                    echo '<td><a href="datosArticulo.php?articulo='.$referencia.'">'.$referencia.'<a/></td><td>'.$nombre.'</td><td>'.$unidades.'</td><td>'.formatoDinero(precioIVASinRedondeo($precio, $fecha)).'</td><td>'.$descuento.'%</td><td>'.formatoDinero($precioTotal-(($precioTotal*$descuento)/100)).'</td><td>'.$comentario.'</td><td>'.$capitulo.'</td><td>'.$subcapitulo.'</td><td><a href="eliminaLineaPresupuesto.php?presupuesto='.$presupuesto.'&lineapresupuesto='.$row2['IdLineaPresupuesto'].'">Eliminar</a></td>';

				}
				
				echo '</table>';								
				
				if ($row['IdVenta'] == NULL)
				{
					echo '<p class="parrafoCentrado"><a href="altaPresupuesto.php?presupuesto='.$presupuesto.'&continuar=si"><input name="continuar" type="submit" value="Continuar presupuesto" class="boton" /></a></p>';
					echo '<p class="parrafoCentrado"><a href="venderPresupuestoPrev.php?presupuesto='.$presupuesto.'"><input name="vender" type="submit" value="Vender presupuesto" class="boton" /></a></p>';
					
					if($stmt->rowcount() == 0)
						echo '<p class="parrafoCentrado"><a href="borrarPresupuesto.php?presupuesto='.$presupuesto.'"><input name="borrarPresupuesto" type="button" value="Borrar presupuesto" class="boton" /></a></p>';
				}
				else
				{
					echo '<p class="parrafoCentrado">Presupuesto vendido.</p>';  
                    echo '<p class="parrafoCentrado"><a href="datosVenta.php?venta='.$IdVenta.'"><input name="ventaCorrespondiente" type="button" value="Venta correspondiente" class="boton" /></a></p>';
					echo '<p class="parrafoCentrado"><a href="altaPresupuesto.php?presupuesto='.$presupuesto.'&continuar=si"><input name="continuar" type="submit" value="Continuar presupuesto" class="boton" /></a>&nbsp;';
					echo '<a href="venderPresupuestoPrev.php?presupuesto='.$presupuesto.'"><input name="vender" type="submit" value="Actualizar venta" class="boton" /></a></p>';
				}
				
			}
		}
		catch(PDOException $e ) {
			// tratamiento del error
			die("Error PDO: ".$e->GetMessage());
		}
	}

	include_once('inc/footer.php');

?>