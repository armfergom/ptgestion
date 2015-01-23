<?php

	echo '<head><link rel="stylesheet" type="text/css" href="css/style2.css" /></head><body>';

	include_once ('inc/db_connect.php');
	include_once ('inc/misc.php');
	
	echo '<div class="dinA4Inv">';
	
	echo '<b>Resumen de ventas:</b><br /><br />';


	if (!isset($_REQUEST['tipo']))
		$query = "SELECT IdVenta, FechaVenta, Descuento, IdCliente FROM venta ORDER BY FechaVenta DESC";
	if ($_REQUEST['tipo'] == 'ticket')
		$query = "SELECT IdVenta, FechaVenta, Descuento, IdCliente FROM venta WHERE IdCliente is NULL ORDER BY FechaVenta DESC";
	if ($_REQUEST['tipo'] == 'factura')
		$query = "SELECT IdVenta, FechaVenta, Descuento, IdCliente FROM venta WHERE IdCliente is not NULL ORDER BY FechaVenta DESC";



	$stmt = $dbh -> query($query);
	
	$mes = 0;
	$año = 0;
	$totalBecara = 0;
	$totalOtros = 0;
	
	//para cada venta
	foreach ($stmt as $row)
	{
		$cliente = $row['IdCliente'];
		$nuevoMes = substr($row['FechaVenta'], 5, 2);
		$año = substr($row['FechaVenta'], 0, 4);
		$descuentoVenta = $row['Descuento'];
				
		//al cambiar de mes
		if ($nuevoMes != $mes)
		{
			//mostramos la información del mes que acabamos de terminar de calcular (si no era el primero)
			if ($mes != 0)
			{
				$textoMes = mes($mes);
				echo "<b>$textoMes de $año</b><br />";
				
				$tb = formatoDinero($totalBecara);
				echo "Becara - $tb €<br />";
				$to = formatoDinero($totalOtros);
				echo "Otros proveedores - $to €</p>";				
			}
			
			$mes = $nuevoMes;
			//reiniciamos contadores
			$totalBecara = 0;
			$totalOtros = 0;
		}
				
		//obtenemos información sobre cada línea de esa venta
		$IdVenta = $row['IdVenta'];
		$query2 = "SELECT Referencia, Unidades, Precio, Descuento FROM lineaventa WHERE IdVenta = $IdVenta";
		
		$stmt2 = $dbh -> query($query2);
		
			//para cada linea venta
			foreach ($stmt2 as $row2)
			{
				$unidades = $row2['Unidades'];
				$precio = $row2['Precio'];
				$Referencia = $row2['Referencia'];
				//descuento total por artículo. Cuenta el descuento concreto de ese artículo más el descuento proporcional a ese artículo si la venta de ese artículo tenía un descuento general.
				$descuento = $row2['Descuento'] + $descuentoVenta;
				
				//Se tratan distintas las ventas de tickets que las de factura
				if ($cliente == null)
					$total = ($unidades * precioIVA($precio, $row['FechaVenta']))*(100 - $descuento)/100;
				else
					$total = ($unidades * precioIVASinRedondeo($precio, $row['FechaVenta']))*(100 - $descuento)/100;
					
				
				//y vemos si se trata de un artículo de Becara o de otros
				$query3 = "SELECT IdProveedor FROM articulo WHERE Referencia = $Referencia";
				
				$stmt3 = $dbh -> query($query3);
				
				$row3 = $stmt3 -> fetch();
				
				if ($row3['IdProveedor'] == 1)
					$totalBecara += $total;
				else
					$totalOtros += $total;
			}

	}
	
	//mostramos la información del último mes
	if ($mes != 0)
	{
		$textoMes = mes($mes);
		echo "<b>$textoMes de $año</b><br />";
		
		$tb = formatoDinero($totalBecara);
		echo "Becara - $tb €<br />";
		$to = formatoDinero($totalOtros);
		echo "Otros proveedores - $to €</p>";	
	}
			
	echo '</table></div>';

	disconnect($dbh);
	
?>