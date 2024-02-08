<?php

	echo '<head><link rel="stylesheet" type="text/css" href="css/printTicket.css"/></head><body>';

	include_once ('inc/db_connect.php');
	include_once ('inc/misc.php');
	

	//mostramos las etiquetas
	if (isset($_REQUEST['venta']))
	{	
		$regalo = $_REQUEST['regalo'];
		$venta=$_REQUEST['venta'];
		$tienda=$_REQUEST['tienda'];
		
		$sql="SELECT * FROM ticket WHERE IdVenta=$venta";
		$stmt0 = $dbh -> query($sql);
	
		if (($stmt0 -> rowcount()) == 0){
			$stmt = $dbh -> query("SELECT YEAR(FechaVenta) as AnoVenta FROM venta WHERE IdVenta=$venta");
			$row = $stmt -> fetch();
			$anoVenta = $row['AnoVenta'];
			$sql2="INSERT INTO ticket (IdVenta, AnoTicket) VALUES ($venta, $anoVenta)";
			$dbh -> exec($sql2);
			$ticketId= $dbh -> lastInsertId();
		}
		else{
			$row0=$stmt0 -> fetch();
			$ticketId=$row0['IdTicket'];
		}

		$numT=calculaNumeroTicket($venta);
		
		$query="SELECT * FROM lineaventa WHERE IdVenta = $venta";
		$query2="SELECT * FROM venta WHERE IdVenta = $venta";
		$stmt = $dbh -> query($query);
		$stmt2 = $dbh -> query($query2);
		$row2 = $stmt2 -> fetch();
			
		$fecha = $row2['FechaVenta'];
		

		echo '<table class="tabla-ticket">';
		
		imprimirCabeceraTicket($tienda);
		
		echo '<tr><td colspan="3" align="left">Fecha: '.$row2['FechaVenta'].'</td></tr>';
		echo '<tr><td colspan="3" align="left">N Ticket: '.$numT.'</td></tr>';
		echo '<tr><td colspan="3" align="left"><br /></td></tr>';
	
		echo '<tr><td align="left">DESCRIP.</td><td>CANTIDAD</td>';
		
		if ($regalo == 'no')
			echo '<td>IMPORTE</td></tr>';
		else
			echo '<td></td></tr>';
		
		$precioTotal = 0;
		
		foreach ($stmt as $row)
		{
			$precioUnidad=$row['Precio'];
			$unidades=$row['Unidades'];
			$referencia=$row['Referencia'];
			$comentario=$row['Comentario'];
			$query3="SELECT Nombre FROM articulo WHERE Referencia='$referencia'";
			$stmt3 = $dbh -> query($query3);
			$row3 = $stmt3 -> fetch();
			$nombre=$row3['Nombre'];
			$nombre=substr($nombre,0,10);
			
			if ($comentario != null)
				$descr=$comentario;
			else
				$descr=$nombre;
			
			$precioTotal += formatoDinero(precioIVA($unidades*$precioUnidad,$fecha));
			
			echo '<tr><td align="left">'.$referencia.'&nbsp '.substr($descr,0,5).'</td><td>'.$unidades.'</td>';
			
			if ($regalo == 'no')
				echo '<td>'.formatoDinero(precioIVA($unidades*$precioUnidad,$fecha)).'</td></tr>';
			else
				echo '<td></td></tr>';
			
		}
		echo '<tr><td colspan="3" align="left"><br /></td></tr>';
		
		if ($regalo == 'no')
		{
			echo '<tr><td colspan="2" align="left">Total Ticket:</td><td>'.formatoDinero($precioTotal).'</td></tr>';
			if ($row2['Descuento'] != 0)
			{
				echo '<tr><td colspan="2" align="left">Descuento:</td><td>'.$row2['Descuento'].'%</td></tr>';
				echo '<tr><td colspan="2" align="left">Total con descuento:</td><td>'.formatoDinero(redondea(precioVenta($venta))).'</td></tr>';
			}
			echo '<tr><td colspan="3" align="left"><br /></td></tr>';
			echo '<tr><td colspan="3" align="left">NO SE ADMITEN DEVOLUCIONES</td></tr>';
			echo '<tr><td colspan="3" align="left">&nbsp</td></tr>';				

		}
		else
			echo '<tr><td colspan="3" align="left">TICKET REGALO</td></tr>';

		echo '</table>';
	} 

	disconnect($dbh);
	
?>