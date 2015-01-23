<?php

	echo '<head><link rel="stylesheet" type="text/css" href="css/printFac.css" media="print"/></head><body>';

	include_once ('inc/db_connect.php');
	include_once ('inc/misc.php');

	if (isset($_REQUEST['venta']))
	{	
		$venta=$_REQUEST['venta'];
		
		
		$query2="SELECT * FROM venta WHERE IdVenta = $venta";
		$stmt = $dbh -> query($query);
		$stmt2 = $dbh -> query($query2);
		$row2 = $stmt2 -> fetch();
		$fecha = $row2['FechaVenta'];
		
		$ant=$row2['Antiguedad'];
		$descuento=$row2['Descuento'];
		$cliente=$row2['IdCliente'];
		$query3="SELECT * FROM cliente WHERE IdCliente=$cliente";
		$stmt3 = $dbh -> query($query3);
		$row3 = $stmt3 -> fetch();
		$comentario=$_REQUEST['comentario'];
		
		$precioVenta=precioVenta($venta);
		
		if ($ant=='Si'){
			$query4="SELECT * FROM facturaant WHERE IdVenta=$venta";
			$stmt0 = $dbh -> query($query4);
			if (($stmt0 -> rowcount()) == 0){
				$sql2="INSERT INTO facturaant (IdVenta) VALUES ($venta)";
				$dbh -> exec($sql2);
			}
		}
		else{
			$query4="SELECT * FROM factura WHERE IdVenta=$venta";
			$stmt0 = $dbh -> query($query4);
			if (($stmt0 -> rowcount()) == 0){
				$sql2="INSERT INTO factura (IdVenta) VALUES ($venta)";
				$dbh -> exec($sql2);
			}		
		}
		
		$stmt4 = $dbh -> query($query4);
		$row4 = $stmt4 -> fetch();

		
		//Cálculo del número de factura
		if ($ant=='No')
		{
			$numFac = calculaNumeroFactura($venta,$fecha);
		}
		else
		{
			$numFac = calculaNumeroFacturaAntiguedad($venta,$fecha);
		}
		
		
		if ($row3['CP']==0)
			$CP=null;
		else
			$CP=$row3['CP'];
			
		if ($row3['NIF'] == null)
			$NIF=null;
		else
			$NIF='NIF '.$row3['NIF'];
			
		switch($row3['Titulo'])
		{
			case 'Sr. D.':
			case 'Sra. Dª.':
				$cadenaTituloEtc = $row3['Titulo'] . ' ' . $row3['Nombre'] . ' ' . $row3['Apellidos'];
				break;
			case 'Sres. de':
				$cadenaTituloEtc = $row3['Titulo'] . ' ' . $row3['Apellidos'];
				break;
			default:
				$cadenaTituloEtc = $row3['Apellidos'];
				break;
		}

		echo '<table class="tabla-factura-arriba">';
		echo '	<tr class="margen-superior"></tr>
			<tr class="datosCliente1"><td class="margen-lateral"></td><td class="ancho2">'.$cadenaTituloEtc.'</td><td class="alineado-derecha">FRA '.$numFac.'</td><td class="margen-lateral"></td></tr>
			<tr class="datosCliente2"><td class="margen-lateral"></td><td colspan="2">'.$row3['Direccion'].' </td><td class="margen-lateral"></td></tr>
			<tr class="datosCliente3"><td class="margen-lateral"></td><td colspan="2">'.$row3['Localidad'].' '.$CP.'&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp '.$NIF.'</td><td class="margen-lateral"></td></tr></table>
			<table class="tabla-factura">
			<tr class="alturaNormal"><td class="margen-lateral"></td><td class="comentarioFacRes" colspan="2"><p class="parrafoCentrado">'.$comentario.'</p></td><td class="margen-lateral"></td></tr>';
	
		$lineas=1;
		$baseImponible=calculaBaseImponible($venta,'venta');
		
		if ($descuento!=0){
			echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td class="comentarioFacRes">Base imponible<br />'.cuotaIVA($fecha).'<br /><b>Descuento</b><br /><b>TOTAL(IVA)</b></td><td class="alineado-derecha">'.formatoDinero($baseImponible).' €<br/>'. formatoDinero(impuestoIVA($baseImponible, $fecha)) .' €<br/>'.$descuento.' %<br />'.formatoDinero(precioVenta($venta)) .' €</td><td class="margen-lateral"></td></tr>';				
		}
		else{
			echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td class="comentarioFacRes">Base imponible<br />'.cuotaIVA($fecha).'<br /><b>TOTAL(IVA)</b></td><td class="alineado-derecha">'.formatoDinero($baseImponible).' €<br/>'. formatoDinero(impuestoIVA($baseImponible, $fecha)) .' €<br/>'.formatoDinero(precioVenta($venta)).' €</td><td class="margen-lateral"></td></tr>';				
		}
			
		
		while ($lineas <9){
			echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td class="comentarioFacRes"></td><td class="alineado-derecha"></td><td class="margen-lateral"></td></tr>';
			$lineas++;
		}
		echo '</table>';
		echo '<table class="tabla-factura-abajo"><tr class="margen-superior-esp"><td class="margen-lateral"></td><td><b>Sevilla</b>, '.fechaFormatoLargo($row2['FechaVenta']).'</td><td class="margen-lateral"></td></tr></table>';
	}
	
	if (isset($_REQUEST['presupuesto']))
	{	
		$presupuesto=$_REQUEST['presupuesto'];
		$query2="SELECT * FROM presupuesto WHERE IdPresupuesto = $presupuesto";
		$stmt2 = $dbh -> query($query2);
		$row2 = $stmt2 -> fetch();
		$descuento=$row2['Descuento'];
		$cliente=$row2['IdCliente'];
		$query3="SELECT * FROM cliente WHERE IdCliente=$cliente";
		$stmt3 = $dbh -> query($query3);
		$row3 = $stmt3 -> fetch();
		
		$IdVenta=$row2['IdVenta'];
            $queryFac="SELECT * FROM factura WHERE IdVenta=$IdVenta";
            $stmtFac = $dbh -> query($queryFac);
            if (($stmtFac -> rowcount()) > 0){
                $rowFac = $stmtFac -> fetch();
            }
            else{
                $sqlFac="INSERT INTO factura (IdVenta) VALUES ($IdVenta)";
                $dbh->exec($sqlFac);
            }
		
		$query="SELECT FechaVenta,Antiguedad FROM venta WHERE IdVenta=$IdVenta";
		$stmt=$dbh->query($query);
		$row=$stmt->fetch();
		$fecha = $row['FechaVenta'];
		$ant = $row['Antiguedad'];
		$comentario=$_REQUEST['comentario'];
		
		//Cálculo del número de factura
		if ($ant=='No')
		{
			$numFac = calculaNumeroFactura($IdVenta,$fecha);
		}
		else
		{
			$numFac = calculaNumeroFacturaAntiguedad($IdVenta,$fecha);
		}
		
		switch($row3['Titulo'])
		{
			case 'Sr. D.':
			case 'Sra. Dª.':
				$cadenaTituloEtc = $row3['Titulo'] . ' ' . $row3['Nombre'] . ' ' . $row3['Apellidos'];
				break;
			case 'Sres. de':
				$cadenaTituloEtc = $row3['Titulo'] . ' ' . $row3['Apellidos'];
				break;
			default:
				$cadenaTituloEtc = $row3['Apellidos'];
				break;
		}

		echo '<table class="tabla-factura-arriba">';
		echo '	<tr class="margen-superior"></tr>
			<tr class="datosCliente1"><td class="margen-lateral"></td><td class="ancho2">'.$cadenaTituloEtc.'</td><td class="alineado-derecha">FRA '.$numFac.'</td><td class="margen-lateral"></td></tr>
			<tr class="datosCliente2"><td class="margen-lateral"></td><td colspan="2">'.$row3['Direccion'].' </td><td class="margen-lateral"></td></tr>
			<tr class="datosCliente3"><td class="margen-lateral"></td><td colspan="2">'.$row3['Localidad'].' '.$CP.'&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp '.$NIF.'</td><td class="margen-lateral"></td></tr></table>
			<table class="tabla-factura">
			<tr class="alturaNormal"><td class="margen-lateral"></td><td class="comentarioFacRes">'.$comentario.'</td><td class="alineado-derecha"></td><td class="margen-lateral"></td></tr>';
	
		$lineas=1;
		$baseImponible=calculaBaseImponible($presupuesto,'presupuesto');
		
		if ($descuento!=0){
			echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td class="comentarioFacRes">Base imponible<br />'.cuotaIVA($fecha).'<br /><b>Descuento</b><br /><b>TOTAL(IVA)</b></td><td class="alineado-derecha">'.formatoDinero($baseImponible).' €<br/>'. formatoDinero(impuestoIVA($baseImponible, $fecha)) .' €<br/>'.$descuento.' %<br />'.formatoDinero(precioPresupuesto($presupuesto)) .' €</td><td class="margen-lateral"></td></tr>';				
		}
		else{
			echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td class="comentarioFacRes">Base imponible<br />'.cuotaIVA($fecha).'<br /><b>TOTAL(IVA)</b></td><td class="alineado-derecha">'.formatoDinero($baseImponible).' €<br/>'. formatoDinero(impuestoIVA($baseImponible, $fecha)) .' €<br/>'.formatoDinero(precioPresupuesto($presupuesto)).' €</td><td class="margen-lateral"></td></tr>';				
		}
			
		
		while ($lineas <9){
			echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td class="comentarioFacRes"></td><td class="alineado-derecha"></td><td class="margen-lateral"></td></tr>';
			$lineas++;
		}
		echo '</table>';
		echo '<table class="tabla-factura-abajo"><tr class="margen-superior-esp"><td class="margen-lateral"></td><td><b>Sevilla</b>, '.fechaFormatoLargo($row['FechaVenta']).'</td><td class="margen-lateral"></td></tr></table>';
	}

	disconnect($dbh);
	
?>