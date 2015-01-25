<?php

	echo '<head><link rel="stylesheet" type="text/css" href="css/printFac.css" media="print"/></head><body>';

	include_once ('inc/db_connect.php');
	include_once ('inc/misc.php');


	if (isset($_REQUEST['venta']))
	{	
		$venta=$_REQUEST['venta'];
		$electronica=$_REQUEST['electronica'];
	
		$query="SELECT * FROM lineaventa WHERE IdVenta = $venta";
		$query2="SELECT * FROM venta WHERE IdVenta = $venta";
		$stmt = $dbh -> query($query);
		$stmt2 = $dbh -> query($query2);
		$row2 = $stmt2 -> fetch();
		$fecha = $row2['FechaVenta'];
		$descuentoVenta=$row2['Descuento'];
		$cliente=$row2['IdCliente'];
		$ant=$row2['Antiguedad'];
		$query3="SELECT * FROM cliente WHERE IdCliente=$cliente";
		$stmt3 = $dbh -> query($query3);
		$row3 = $stmt3 -> fetch();
		$anoVenta=substr($fecha,0,4);
		$precioVenta=precioVenta($venta);
		
		if ($ant=='Si'){
			$query4="SELECT * FROM facturaant WHERE IdVenta=$venta";
			$stmt0 = $dbh -> query($query4);
			if (($stmt0 -> rowcount()) == 0){
				$sql2="INSERT INTO facturaant (IdVenta) VALUES ($venta)";
				$dbh -> exec($sql2);
				$query4="SELECT IdFactura FROM facturaant WHERE IdVenta=$venta";
			}
		}
		else{
			$query4="SELECT * FROM factura WHERE IdVenta=$venta";
			$stmt0 = $dbh -> query($query4);
			if (($stmt0 -> rowcount()) == 0){
				$sql2="INSERT INTO factura (IdVenta) VALUES ($venta)";
				$dbh -> exec($sql2);
				$query4="SELECT IdFactura FROM factura WHERE IdVenta=$venta";
			}		
		}
		
		$stmt4 = $dbh -> query($query4);
		$row4 = $stmt4 -> fetch();
		$IdFactura=$row4['IdFactura'];
		
		//Cálculo del número de factura
		if ($ant=='No')
		{
			$numFac = calculaNumeroFactura($venta,$fecha);
		}
		else
		{
			$numFac = calculaNumeroFacturaAntiguedad($venta,$fecha);
		}
		
		$query5="SELECT * FROM lineaventa WHERE IdVenta=$venta AND Descuento <> 0";
		$stmt5 = $dbh -> query($query5);
		
		if (($stmt5 -> rowcount())!=0)
			$conDesc=true;
		else
			$conDesc=false;
		
		$nuevaPag=true;
		$baseImponible=0;
		
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
			
		foreach ($stmt as $row){
			if ($nuevaPag){
				//Si se quiere imprimir la factura en papel o si se quiere imprimir la factura electronica
				if ($electronica == 'no'){
					echo '<table class="tabla-factura-arriba">';
					echo '	<tr class="margen-superior"></tr>
						<tr class="datosCliente1"><td class="margen-lateral"></td><td class="ancho2">'.$cadenaTituloEtc.'</td><td class="alineado-derecha"> FRA '.$numFac.'</td><td class="margen-lateral"></td></tr>
						<tr class="datosCliente2"><td class="margen-lateral"></td><td colspan="2">'.$row3['Direccion'].' </td><td class="margen-lateral"></td></tr>
						<tr class="datosCliente3"><td class="margen-lateral"></td><td colspan="2">'.$row3['Localidad'].' '.$CP.'&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp '.$NIF.'</td><td class="margen-lateral"></td></tr></table>
						<table class="tabla-factura">';
				}
				else{
					echo '<table class="tabla-imagen-cabecera"><tr><td><img class ="imagenFactura" src="./Imagenes/cabeceraFacturaElectronica.png" alt="La imagen no se ha podido cargar"></img></td><tr></table>';
					echo '<table class="tabla-factura-arriba-electronica">';
					echo '<tr class="datosCliente1"><td class="margen-lateral"></td><td class="ancho2">'.$cadenaTituloEtc.'</td><td class="alineado-derecha"> FRA '.$numFac.'</td><td class="margen-lateral"></td></tr>
						<tr class="datosCliente2"><td class="margen-lateral"></td><td colspan="2">'.$row3['Direccion'].' </td><td class="margen-lateral"></td></tr>
						<tr class="datosCliente3"><td class="margen-lateral"></td><td colspan="2">'.$row3['Localidad'].' '.$CP.'&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp '.$NIF.'</td><td class="margen-lateral"></td></tr></table>
						<table class="tabla-factura">';
				}
				
				if ($conDesc)
					echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td><b>Ref. Art.</b></td><td><b>Descripción</b></td><td><b>Precio Ud.</b></td><td><b>Desc.</b></td><td><b>Uds.</b></td><td class="alineado-derecha"><b>Total</b></td><td class="margen-lateral"></td></tr>';
				else
					echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td><b>Ref. Art.</b></td><td><b>Descripción</b></td><td><b>Precio Ud.</b></td><td><b>Uds.</b></td><td class="alineado-derecha"><b>Total</b></td><td class="margen-lateral"></td></tr>';
					
					$nuevaPag=false;	
					$lineas=0;
			}

				$precioUnidad=$row['Precio'];
				$unidades=$row['Unidades'];
				$descuento=$row['Descuento'];
				
				if ($descuento != 0){
					$baseImponible+=($precioUnidad-(($precioUnidad*$descuento)/100))*$unidades;
				}
				else{
					$baseImponible+=($precioUnidad*$unidades);
				}

				$referencia=$row['Referencia'];
				$comentario=$row['Comentario'];
				$query4="SELECT Nombre FROM articulo WHERE Referencia='$referencia'";
				$stmt4 = $dbh -> query($query4);
				$row4 = $stmt4 -> fetch();
				$nombre=$row4['Nombre'];

				$descr=$nombre.' '.$comentario;

				if ($conDesc){
					if ($descuento == 0)
						echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td>'.$referencia.'</td><td class="descripcion">'.$descr.'</td><td>'.formatoDinero($precioUnidad).' €</td><td>'.$descuento.' %</td><td class="alineado-centro">'.$unidades.'</td><td class="alineado-derecha">'.formatoDinero($precioUnidad*$unidades).' €</td><td class="margen-lateral"></td></tr>';
					else
						echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td>'.$referencia.'</td><td class="descripcion">'.$descr.'</td><td>'.formatoDinero($precioUnidad).' €</td><td>'.$descuento.' %</td><td class="alineado-centro">'.$unidades.'</td><td class="alineado-derecha">'.formatoDinero(($precioUnidad*$unidades)-((($precioUnidad*$unidades)*$descuento)/100)).' €</td><td class="margen-lateral"></td></tr>';

				}
				else{
					echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td>'.$referencia.'</td><td class="descripcion">'.$descr.'</td><td>'.formatoDinero($precioUnidad).' €</td><td class="alineado-centro">'.$unidades.'</td><td class="alineado-derecha">'.formatoDinero($precioUnidad*$unidades).' €</td><td class="margen-lateral"></td></tr>';
				}
				$lineas++;

				
				if ($lineas == 9){
					echo '</table>';
					echo '<table class="tabla-factura-abajo"><tr class="margen-superior-esp"><td class="margen-lateral"></td><td><b>Sevilla</b>, '.fechaFormatoLargo($row2['FechaVenta']).'</td><td class="margen-lateral"></td></tr></table>';
					//echo '<br class="saltoPagina"/>';
					$nuevaPag=true;
				}
		}
		if ($lineas <9){
        
           		if ($conDesc){
					if ($descuentoVenta!=0){
						echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td></td><td class="descripcion"></td><td>Suma total base imponible<br />'.cuotaSumaIVA($fecha).'<br /><b>Descuento</b><br /><b>TOTAL I.V.A INCLUIDO</b></td><td></td><td></td><td class="alineado-derecha">'.formatoDinero($baseImponible).' €<br/>'. formatoDinero(impuestoIVA($baseImponible, $fecha)) .' €<br/>'.$descuentoVenta.' %<br />'.formatoDinero(precioVenta($venta)) .' €</td><td class="margen-lateral"></td></tr>';				
					}
					else{
						echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td></td><td class="descripcion"></td><td>Suma total base imponible<br />'.cuotaSumaIVA($fecha).'<br /><b>TOTAL I.V.A INCLUIDO</b></td><td></td><td></td><td class="alineado-derecha">'.formatoDinero($baseImponible).' €<br/>'. formatoDinero(impuestoIVA($baseImponible, $fecha)).' €<br/>'.formatoDinero(precioVenta($venta)).' €</td><td class="margen-lateral"></td></tr>';				
					}
				}
				else{
					echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td></td><td class="descripcion"></td><td>Suma total base imponible<br />'.cuotaSumaIVA($fecha).'<br /><b>TOTAL I.V.A INCLUIDO</b></td><td></td><td class="alineado-derecha">'.formatoDinero($baseImponible) .' €<br/>'. formatoDinero(impuestoIVA($baseImponible, $fecha)) .' €<br/>'.formatoDinero(precioVenta($venta)).' €</td><td class="margen-lateral"></td></tr>';				
				}
		}
		else{
				//Si se quiere imprimir la factura en papel o si se quiere imprimir la factura electronica
				if ($electronica == 'no'){
					echo '<table class="tabla-factura-arriba">';
					echo '	<tr class="margen-superior"></tr>
						<tr class="datosCliente1"><td class="margen-lateral"></td><td class="ancho2">'.$cadenaTituloEtc.'</td><td class="alineado-derecha"> FRA '.$numFac.'</td><td class="margen-lateral"></td></tr>
						<tr class="datosCliente2"><td class="margen-lateral"></td><td colspan="2">'.$row3['Direccion'].' </td><td class="margen-lateral"></td></tr>
						<tr class="datosCliente3"><td class="margen-lateral"></td><td colspan="2">'.$row3['Localidad'].' '.$CP.'&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp '.$NIF.'</td><td class="margen-lateral"></td></tr></table>
						<table class="tabla-factura">';
				}
				else{
					echo '<table class="tabla-imagen-cabecera"><tr><td><img class ="imagenFactura" src="./Imagenes/cabeceraFacturaElectronica.png" alt="La imagen no se ha podido cargar"></img></td><tr></table>';
					echo '<table class="tabla-factura-arriba-electronica">';
					echo '<tr class="datosCliente1"><td class="margen-lateral"></td><td class="ancho2">'.$cadenaTituloEtc.'</td><td class="alineado-derecha"> FRA '.$numFac.'</td><td class="margen-lateral"></td></tr>
						<tr class="datosCliente2"><td class="margen-lateral"></td><td colspan="2">'.$row3['Direccion'].' </td><td class="margen-lateral"></td></tr>
						<tr class="datosCliente3"><td class="margen-lateral"></td><td colspan="2">'.$row3['Localidad'].' '.$CP.'&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp '.$NIF.'</td><td class="margen-lateral"></td></tr></table>
						<table class="tabla-factura">';
				}
				
				if ($conDesc)
					echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td><b>Ref. Art.</b></td><td><b>Descripción</b></td><td><b>Precio Ud.</b></td><td><b>Desc.</b></td><td><b>Uds.</b></td><td class="alineado-derecha"><b>Total</b></td><td class="margen-lateral"></td></tr>';
				else
					echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td><b>Ref. Art.</b></td><td><b>Descripción</b></td><td><b>Precio Ud.</b></td><td><b>Uds.</b></td><td class="alineado-derecha"><b>Total</b></td><td class="margen-lateral"></td></tr>';				
				
				if ($conDesc){
					if ($descuentoVenta!=0){
						echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td></td><td class="descripcion"></td><td>Suma total base imponible<br />'.cuotaSumaIVA($fecha).'<br /><b>Descuento</b><br /><b>TOTAL I.V.A INCLUIDO</b></td><td></td><td></td><td class="alineado-derecha">'.formatoDinero($baseImponible).' €<br/>'. formatoDinero(impuestoIVA($baseImponible, $fecha)) .' €<br/>'.$descuentoVenta.' %<br />'.formatoDinero(precioVenta($venta)) .' €</td><td class="margen-lateral"></td></tr>';				
					}
					else{
						echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td></td><td class="descripcion"></td><td>Suma total base imponible<br />'.cuotaSumaIVA($fecha).'<br /><b>TOTAL I.V.A INCLUIDO</b></td><td></td><td></td><td class="alineado-derecha">'.formatoDinero($baseImponible).' €<br/>'. formatoDinero(impuestoIVA($baseImponible, $fecha)).' €<br/>'.formatoDinero(precioVenta($venta)).' €</td><td class="margen-lateral"></td></tr>';				
					}
				}
				else{
					echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td></td><td class="descripcion"></td><td>Suma total base imponible<br />'.cuotaSumaIVA($fecha).'<br /><b>TOTAL I.V.A INCLUIDO</b></td><td></td><td class="alineado-derecha">'.formatoDinero($baseImponible).' €<br/>'. formatoDinero(impuestoIVA($baseImponible, $fecha)) .' €<br/>'.formatoDinero(precioVenta($venta)).' €</td><td class="margen-lateral"></td></tr>';				
				}
				$lineas=1;
		}
		while ($lineas <9){
			echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td></td><td class="descripcion"></td><td></td><td></td><td class="alineado-derecha"></td><td class="margen-lateral"></td></tr>';
			$lineas++;
		}
		echo '</table>';
		echo '<table class="tabla-factura-abajo"><tr class="margen-superior-esp"><td class="margen-lateral"></td><td><b>Sevilla</b>, '.fechaFormatoLargo($row2['FechaVenta']).'</td><td class="margen-lateral"></td></tr></table>';
	} 

	disconnect($dbh);
	
?>