<?php

	include_once('inc/header.php');
	include_once('inc/misc.php');
	
	$IdPresupuesto = $_REQUEST['presupuesto'];
	$forma = $_REQUEST['forma'];
	$antiguedad = $_REQUEST['antiguedad'];
	
	try
	{
		$query = "SELECT Observaciones,IdCliente,IdVenta FROM presupuesto WHERE IdPresupuesto = $IdPresupuesto";
		$stmt = $dbh->query($query);	
		
		if ($stmt->rowCount() == 1)
		{
			$row = $stmt->fetch();
			
			//si ya existe una venta para ese presupuesto, borramos sus líneas de venta
			$IdVenta = $row['IdVenta'];
			if ($IdVenta != null)
			{
				$query2 = "SELECT IdLineaVenta FROM lineaventa WHERE IdVenta = $IdVenta";
				$stmt2 = $dbh->query($query2);
				foreach ($stmt2 as $row2)
				{
					$lineaVenta = $row2['IdLineaVenta'];
					eliminaLineaVenta($lineaVenta);
				}
			}
		
			$observaciones = $row['Observaciones'];
			$cliente = $row['IdCliente'];
			$descuento = $row['Descuento'];
			
			$query2 = "SELECT Referencia, Unidades, Precio, Comentario FROM lineapresupuesto WHERE IdPresupuesto = $IdPresupuesto";
			$stmt2 = $dbh->query($query2);
			
			$error = false;
			$errores = '<ul>';
			//habrá que ver si se puede hacer la venta (tenemos suficientes unidades de cada artículo)
			foreach ($stmt2 as $row2)
			{
				$referencia = $row2['Referencia'];
				$unidades = $row2['Unidades'];
				
				if (!esIntangible($referencia))
				{
					//ver que tenemos al menos $unidades unidades del artículo $referencia
					$query3 = "SELECT Unidades FROM articulo WHERE Referencia = '$referencia'";
					$stmt3 = $dbh -> query($query3);
					$row3 = $stmt3 -> fetch();

					if ($row3['Unidades'] < $unidades)
					{
						$error = true;
						$errores .= "<li>No se pueden vender $unidades unidades del artículo <a href=\"datosArticulo.php?articulo=$referencia\">$referencia</a> porque no se dispone de tantas en stock.</li>";
					}
				}					
			}
			
			//si no hubo error
			if (!$error)
			{
				$primera = false;
				//si es la primera vez que vendemos este presupuesto
				if ($IdVenta == null)
				{
					//insertamos la cabecera de la venta
					if ($forma == 'null')
						$sql = "INSERT INTO venta (FechaVenta, FormaPago, Observaciones, IdCliente, Antiguedad, Descuento) VALUES (curdate(), '$forma','$observaciones', $cliente, '$antiguedad',0)";
					else
						$sql = "INSERT INTO venta (FechaVenta,FechaCobro, FormaPago, Observaciones, IdCliente, Antiguedad,Descuento) VALUES (curdate(),curdate(), '$forma','$observaciones', $cliente, '$antiguedad',0)";
					
					$dbh->exec($sql);
					$IdVenta = $dbh->lastInsertId();
					$primera = true;
				}
				else
				{
					//actualizamos la cabecera de la venta
					if ($forma == 'null')
						$sql = "UPDATE venta SET FechaVenta=curdate(), FechaCobro=null, FormaPago='null', Antiguedad='$antiguedad' WHERE IdVenta = $IdVenta";
					else
						$sql = "UPDATE venta SET FechaVenta=curdate(), FechaCobro=curdate(), FormaPago='$forma', Antiguedad='$antiguedad' WHERE IdVenta = $IdVenta";
					
					$dbh->exec($sql);
				}
				
				//para cada linea del presupuesto
				$query2 = "SELECT Referencia, Unidades, Precio, Descuento, Comentario FROM lineapresupuesto WHERE IdPresupuesto = $IdPresupuesto";
				$stmt2 = $dbh->query($query2);
					
				foreach ($stmt2 as $row2)
				{
					$referencia = $row2['Referencia'];
					$unidades = $row2['Unidades'];
					$precio = $row2['Precio'];
					$descuento = $row2['Descuento'];
					$comentario = $row2['Comentario'];
					
					//la insertamos en la linea de venta
					$sql = "INSERT INTO lineaventa (Referencia, IdVenta, Unidades, Precio, Descuento, Comentario) VALUES ('$referencia', $IdVenta, $unidades, $precio, $descuento, '$comentario')";
					$dbh->exec($sql);
					
					//y decrementamos las unidades de ese artículo
					if (!esIntangible($referencia))
					{
						$query3 = "SELECT Unidades FROM articulo WHERE Referencia = '$referencia'";
						$stmt3 = $dbh -> query($query3);
						$row3 = $stmt3 -> fetch();
						$nuevasUnidades = $row3['Unidades'] - $unidades;

						$sql = "UPDATE articulo SET Unidades = $nuevasUnidades WHERE Referencia = '$referencia'";
						$dbh -> exec($sql);
					}	
				}
				
				//si es la primera vez
				if ($primera)
				{
					//marcamos el presupuesto como vendido
					$sql = "UPDATE presupuesto SET IdVenta = $IdVenta WHERE IdPresupuesto = $IdPresupuesto";
					$dbh->exec($sql);
					
					//mostramos información por pantalla
					echo '<p class="parrafoCentrado">Venta dada de alta.<br /><a href="datosVenta.php?venta='.$IdVenta.'">Ver venta.</a></p>';
				}
				else
				{
					//mostramos información por pantalla
					echo '<p class="parrafoCentrado">Venta actualizada.<br /><a href="datosVenta.php?venta='.$IdVenta.'">Ver venta.</a></p>';
				}
			}
			//si hubo error (no se puede vender)
			else
			{
				echo '<div class="erroresFormularios"><ul>'.$errores.'</ul></div>';
				echo '<p class="parrafoCentrado"><a href="datosPresupuesto.php?presupuesto='.$IdPresupuesto.'">Volver al presupuesto.</a></p>';
			}
		}
	}
	catch(PDOException $e ) 
	{
		// tratamiento del error
		die("Error PDO: ".$e->GetMessage());
	}
	
	include_once('inc/footer.php');
		
?>