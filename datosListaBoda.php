<?php
	
	include_once('inc/header.php');	
	if(isset($_REQUEST['listaboda']))
	{
		$listaboda = $_REQUEST['listaboda'];
	
		//obtemos los atributos de esa lista de boda
		try 
		{
			$query = "SELECT IdListaBoda, Fecha, Observaciones,IdCliente FROM listaboda WHERE IdListaBoda = $listaboda";
			$stmt = $dbh->query($query);							
			
			if ($stmt->rowCount() == 1)
			{
				$row = $stmt->fetch();
				$fecha = $row['Fecha'];
				echo '
					<a href="menuListaBoda.php"><h4>Menú listas de boda</h4></a>
					<table class="tabla-centrada">
				';
				
				echo '<table class="tabla-listado">';
							
				//cabecera de la tabla
				echo '<tr class="filaImpar"><td>Id</td><td>Fecha</td>';
					
				echo '<td>Precio total (con IVA)</td></tr>';
				
				echo '<tr class="filaPar">';
				
				echo '<td>'.$row['IdListaBoda'].'</td>';
				echo '<td>'.$row['Fecha'].'</td>';	

				echo '<td>'.precioListaBoda($row['IdListaBoda']).'</td>';
					
				echo '</table>';
				
				if ($row['Observaciones'] != null)
					echo '<p class=parrafoCentrado>Observaciones: '.$row['Observaciones'].'';
					
				echo '<p class="parrafoCentrado"><a href="imprimirListaBodaPrev.php?listaBoda='.$listaboda.'"><input name="imprimirListaBoda" type="button" value="Imprimir lista de boda" class="boton" /></a></p>';				

			
				if ($row['IdCliente']!=null){
					$cliente=$row['IdCliente'];
					$queryaux="SELECT Nombre,Apellidos,Titulo FROM cliente WHERE IdCliente=$cliente";
					$stmtaux = $dbh->query($queryaux);	
					$rowaux = $stmtaux->fetch();
					echo '<p class="parrafoCentrado">Cliente que solicita la factura: <a href="datosCliente.php?cliente='.$cliente.'">'.$rowaux['Apellidos'].' '.$rowaux['Titulo'].' '.$rowaux['Nombre'].'</a>';
				}
			

				//mostramos la lista de la lista de boda en detalle
				echo '<p class="parrafoCentrado">Detalle de la lista de boda:<p>';
				
				$query = "SELECT Referencia, Unidades, Precio, Comentario, IdLineaListaBoda FROM linealistaboda WHERE IdListaBoda = $listaboda";
				$stmt = $dbh->query($query);
				
				echo '<table class="tabla-listado"><tr class="cabeceraTabla"><td><b>Referencia</b></td><td><b>Nombre</b></td><td><b>Unidades</b></td><td><b>Precio unidad (con IVA)</b></td><td><b>Precio total (con IVA)</b></td><td><b>Comentario</b></td><td></td></tr>';
				
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
					
					$precioTotal = $unidades * precioIVA($precio,$fecha);
					
					echo '<td><a href="datosArticulo.php?articulo='.$referencia.'">'.$referencia.'<a/></td><td>'.$nombre.'</td><td>'.$unidades.'</td><td>'.formatoDinero(precioIVA($precio,$fecha)).'</td><td>'.formatoDinero($precioTotal).'</td><td>'.$row['Comentario'].'</td><td><a href="eliminaLineaListaBoda.php?listaboda='.$listaboda.'&linealistaboda='.$row['IdLineaListaBoda'].'">Eliminar</a></td>';
				}
				
				echo '</table>';
				
				echo '<p class="parrafoCentrado"><a href="altaListaBoda.php?listaboda='.$listaboda.'&continuar=si"><input name="continuar" type="submit" value="Continuar lista" class="boton" /></a></p>';
					
				if($stmt->rowcount() == 0)
					echo '<p class="parrafoCentrado"><a href="borrarListaBoda.php?listaboda='.$listaboda.'"><input name="borrar" type="button" value="Borrar lista de boda" class="boton" /></a></p>';				
			}
		}
		catch(PDOException $e ) {
			// tratamiento del error
			die("Error PDO: ".$e->GetMessage());
		}
	}

	include_once('inc/footer.php');

?>