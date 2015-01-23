<?php
	
	include_once('inc/header.php');			
	//obtemos las ventas
	try 
	{
		if(!isset($_REQUEST['tipo'])){
			if($_REQUEST['cobradas']=='si'){
				$query = "SELECT IdVenta, FechaVenta,FechaCobro,IdCliente,Antiguedad FROM venta WHERE FechaCobro is not NULL ORDER BY IdVenta DESC";
				$stmt = $dbh->query($query);			
			}
			if($_REQUEST['cobradas']=='no'){
				$query = "SELECT IdVenta, FechaVenta,FechaCobro,IdCliente,Antiguedad FROM venta WHERE FechaCobro is NULL ORDER BY IdVenta DESC";
				$stmt = $dbh->query($query);			
			}
			if(!isset($_REQUEST['cobradas'])){
				$query = "SELECT IdVenta, FechaVenta,FechaCobro,IdCliente,Antiguedad FROM venta ORDER BY IdVenta DESC";
				$stmt = $dbh->query($query);			
			}
			
		}
		$tipo = $_REQUEST['tipo'];
		if($tipo=='ticket' && !isset($_REQUEST['cobradas'])){
			$query = "SELECT IdVenta, FechaVenta,FechaCobro,IdCliente,Antiguedad FROM venta WHERE IdCliente is NULL ORDER BY IdVenta DESC";
			$stmt = $dbh->query($query);
		}
		if($tipo=='factura' && !isset($_REQUEST['cobradas'])){
			$query = "SELECT IdVenta, FechaVenta,FechaCobro,IdCliente,Antiguedad FROM venta WHERE IdCliente is not NULL ORDER BY IdVenta DESC";
			$stmt = $dbh->query($query);
		}
		
		if($tipo =='ticket' && $_REQUEST['cobradas']=='si'){
			$query = "SELECT IdVenta, FechaVenta,FechaCobro,IdCliente,Antiguedad FROM venta WHERE  IdCliente is NULL AND FechaCobro is not NULL ORDER BY IdVenta DESC";
			$stmt = $dbh->query($query);
		}
		if ($tipo =='factura' && $_REQUEST['cobradas']=='si'){
			$query = "SELECT IdVenta, FechaVenta,FechaCobro,IdCliente,Antiguedad FROM venta WHERE IdCliente is not NULL AND FechaCobro is not NULL ORDER BY IdVenta DESC";
			$stmt = $dbh->query($query);
		}
		
		if($tipo=='ticket' && $_REQUEST['cobradas']=='no'){
			$query = "SELECT IdVenta, FechaVenta,FechaCobro,IdCliente,Antiguedad FROM venta WHERE IdCliente is NULL AND FechaCobro is NULL ORDER BY IdVenta DESC";
			$stmt = $dbh->query($query);
		}
		if($tipo=='factura' && $_REQUEST['cobradas']=='no'){
			$query = "SELECT IdVenta, FechaVenta,FechaCobro,IdCliente,Antiguedad FROM venta WHERE IdCliente is not NULL AND FechaCobro is NULL ORDER BY IdVenta DESC";
			$stmt = $dbh->query($query);
		}
		
		
		echo '<a href="menuVentas.php"><h4>Menú ventas</h4></a><a href="menuListadosVentas.php"><h4>Listado de ventas</h4></a>';
		
		if (!isset($tipo))
			echo'<p class="centrado"><a href=resumenVentas.php? target="_new"><button type="button" class="boton">Resumen de ventas</button></a></p>';
		else
			echo'<p class="centrado"><a href=resumenVentas.php?tipo='.$tipo.' target="_new"><button type="button" class="boton">Resumen de ventas</button></a></p>';

		
		if ($_REQUEST['tipo']=='ticket'){
			echo '	<div class="centrado">					
					<a href="listadoVentas.php?tipo=ticket&cobradas=si"> &nbsp Cobradas&nbsp </a>
					<a href="listadoVentas.php?tipo=ticket&cobradas=no"> &nbsp Sin cobrar&nbsp </a>
					<a href="listadoVentas.php?tipo=ticket"> &nbsp Ambas&nbsp </a>
					</div>
					';
		}
		if ($_REQUEST['tipo']=='factura'){
			echo '	<div class="centrado">					
					<a href="listadoVentas.php?tipo=factura&cobradas=si"> &nbsp Cobradas&nbsp </a>
					<a href="listadoVentas.php?tipo=factura&cobradas=no"> &nbsp Sin cobrar&nbsp </a>
					<a href="listadoVentas.php?tipo=factura"> &nbsp Ambas&nbsp </a>
					</div>
					';
		}
		
		if(!isset($_REQUEST['tipo'])){
			echo '	<div class="centrado">
					
					<a href="listadoVentas.php?cobradas=si"> &nbsp Cobradas&nbsp </a>
					<a href="listadoVentas.php?cobradas=no"> &nbsp Sin cobrar&nbsp </a>
					<a href="listadoVentas.php?"> &nbsp Ambas&nbsp </a>
					</div>
					';		
		}
		
		$mes = 0;
		$i=1;
		//para cada venta

			foreach ($stmt as $row)
			{
				$nuevoMes = substr($row['FechaVenta'], 5, 2);
				$año = substr($row['FechaVenta'], 0, 4);
				
				if ($nuevoMes != $mes)
				{
					//si no es la primera vez
					if ($mes != 0)
						echo '</table>';
					
					$mes = $nuevoMes;
					
					$i = 1;
					
					//
					$textoMes = mes($mes);
					echo "<p class=centrado>$textoMes de $año</p>";
					
					//cabecera de la tabla
					echo '<table class="tabla-listado"><tr class="cabeceraTabla"><td><b>Id</b></td><td><b>Fecha venta</b></td><td><b>Fecha cobro</b></td><td><b>Precio total</b></td>';
					echo '<td><b>Antigüedad</b></td><td><b>Tipo</b></td><td><b>Ticket</b></td><td><b>Factura</b></td>';
						
					if ($_REQUEST['cobradas']=='no')
						echo '<td><b>Cobrar</b></td></tr>';
					else
						echo '</tr>';
				}
				
				
				if ($i%2==0)
					echo '<tr class="filaPar">';
				else
					echo '<tr class="filaImpar">';
					
				$i++;
				
				$IdVenta = $row['IdVenta'];
				
				echo '<td><a href="datosVenta.php?venta='.$IdVenta.'">'.$IdVenta.'</a></td>';
				echo '<td>'.$row['FechaVenta'].'</td>';
				
				if($row['FechaCobro']!=null)
					echo '<td>'.$row['FechaCobro'].'</td>';
				else
					echo '<td>---</td>';

				$cliente = $row['IdCliente'];

				//Si es una venta de ticket, entonces redondeamos el resultado para mejor presentaci—n al cliente
				//En otro caso se deja sin redondear para que las cuentas salgan correctamente
				if ($cliente == null)
					$precio = formatoDinero(redondea(precioVenta($IdVenta)));
				else					
					$precio = formatoDinero(precioVenta($IdVenta));

				
				echo "<td>$precio</td>";

				echo '<td>'.$row['Antiguedad'].'</td>';
				
				
				if ($cliente==null)
					echo '<td>Ticket</td><td>'.calculaNumeroTicket($IdVenta).'</td><td>--</td>';	
				else{
					if ($row['Antiguedad'] == 'Si')
						echo '<td>Factura</td><td>--</td><td>'.calculaNumeroFacturaAntiguedad($IdVenta, $row['FechaVenta']).'</td>';
					else
						echo '<td>Factura</td><td>--</td><td>'.calculaNumerofactura($IdVenta, $row['FechaVenta']).'</td>';
				}
					
				if ($_REQUEST['cobradas']=='no')
					echo '<td><a href="cobraVentaPrev.php?venta='.$IdVenta.'&fuente=listado">Cobrar</a></td>';
				
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