<?php
	
	include_once ('inc/db_connect.php');
	
	//devuelve el coste total de una compra
	function costeCompra($compra)
	{
		global $dbh;
		
		try 
		{
			//debemos mirar en la tabla lineaCompra y multiplicar las unidades de cada artículo por su coste
			$coste = 0;
			$query = "SELECT Referencia, Unidades, Coste FROM lineacompra WHERE IdCompra = $compra";
			$stmt = $dbh->query($query);
			
			foreach ($stmt as $row)
			{
				$referencia = $row['Referencia'];
				$unidades = $row['Unidades'];
				
				//incrementamos el coste
				$coste += $row['Coste'] * $unidades;				
			}
			
			return $coste;
		}
		catch(PDOException $e ) {
			// tratamiento del error
			die("Error PDO: ".$e->GetMessage());
		}
	}
	
	function precioVenta($venta)
	{
		global $dbh;
		
		try 
		{
			//debemos mirar en la tabla lineaVenta y multiplicar las unidades de cada artículo por su precio
			$baseImp = 0;
			$query = "SELECT Unidades, Precio, Descuento FROM lineaventa WHERE IdVenta = $venta";
			$stmt = $dbh->query($query);
			
			foreach ($stmt as $row)
			{
				$unidades = $row['Unidades'];
				$precio = $row['Precio'];
				$descuento = $row['Descuento'];
                
				//incrementamos el precio total
				$baseImp += ($precio-(($precio*$descuento)/100))*$unidades;
								
			}
			
			//y aplicamos el descuento total
			$query = "SELECT Descuento, FechaVenta FROM venta WHERE IdVenta = $venta";
			$stmt = $dbh->query($query);
			$row = $stmt->fetch();
			$descuento = $row['Descuento'];
			$fecha = $row['FechaVenta'];
			$ret = $baseImp - (($baseImp*$descuento)/100);
			return precioIVASinRedondeo($ret,$fecha);
		}
		catch(PDOException $e ) {
			// tratamiento del error
			die("Error PDO: ".$e->GetMessage());
		}
	}
	
	function precioPresupuesto($presupuesto)
	{
		global $dbh;
		
		try 
		{
			//debemos mirar en la tabla lineapresupuesto y multiplicar las unidades de cada artículo por su precio
			$preciotOTAL = 0;
			$query = "SELECT Unidades, Precio, Descuento FROM lineapresupuesto WHERE IdPresupuesto = $presupuesto";
			$stmt = $dbh->query($query);
			
			foreach ($stmt as $row)
			{
				$unidades = $row['Unidades'];
				$precio = $row['Precio'];
				$descuento = $row['Descuento'];
				
				//incrementamos el coste
				$precioTotal += ($precio * $unidades) * (100 - $descuento) / 100;				
			}
			
			$query = "SELECT Fecha FROM presupuesto WHERE IdPresupuesto = $presupuesto";
			$stmt = $dbh->query($query);
			$row = $stmt->fetch();
			$fecha = $row['Fecha'];
			$ret = precioIVASinRedondeo($precioTotal,$fecha);
			return $ret;
		}
		catch(PDOException $e ) {
			// tratamiento del error
			die("Error PDO: ".$e->GetMessage());
		}
	}
	
	function precioListaBoda($listaBoda)
	{
		global $dbh;
		
		try 
		{
			//debemos mirar en la tabla lineapresupuesto y multiplicar las unidades de cada artículo por su precio
			$precio = 0;
			$query = "SELECT Unidades, Precio FROM linealistaboda WHERE IdListaBoda = $listaBoda";
			$stmt = $dbh->query($query);
			
			foreach ($stmt as $row)
			{
				$unidades = $row['Unidades'];
				
				//incrementamos el coste
				$precio += $row['Precio'] * $unidades;				
			}
			$query = "SELECT Fecha FROM listaboda WHERE IdListaBoda = $listaBoda";
			$stmt = $dbh->query($query);
			$row = $stmt->fetch();
			$fecha = $row['Fecha'];
			$ret = precioIVA($precio,$fecha);
			return $ret;
		}
		catch(PDOException $e ) {
			// tratamiento del error
			die("Error PDO: ".$e->GetMessage());
		}
	}
	
	function impuestoIVA ($precio, $fecha){
		$ret = (precioIVASinRedondeo($precio,$fecha) - $precio);
		return $ret;
	}
	
	function cuotaIVA ($fecha){
		$mes = substr($fecha, 5, 2);
		$anyo = substr($fecha, 0, 4);
		
		$iva = IVA($fecha);
		
		if ($iva == 1.16)
			$c="I.V.A 16%";	

		if ($iva == 1.18)
			$c="I.V.A 18%";	
			
		if ($iva == 1.21)
			$c="I.V.A 21%";	
				
		return $c;
	}
	
	function cuotaSumaIVA ($fecha) {
		$mes = substr($fecha, 5, 2);
		$anyo = substr($fecha, 0, 4);
		
		$iva = IVA($fecha);
		
		if ($iva == 1.16)
			$c="SUMA I.V.A 16%";	

		if ($iva == 1.18)
			$c="SUMA I.V.A 18%";	
			
		if ($iva == 1.21)
			$c="SUMA I.V.A 21%";	
				
		return $c;
	}

	function precioIVA ($p, $fecha){
		
		$mes = substr($fecha, 5, 2);
		$anyo = substr($fecha, 0, 4);
		
		$p = $p*IVA($fecha);
		
		//return $p;
		//Introducimos redondeo en todos lados menos en las ventas con factura		
		return formatoDinero(redondea($p));
	}
	
	function precioIVASinRedondeo ($p, $fecha){
		
		$p = $p*IVA($fecha);
		
		return $p;
	}
	
	function IVA ($fecha){
	
		$mes = substr($fecha, 5, 2);
		$anyo = substr($fecha, 0, 4);
		
		if ($fecha == null)
			$iva = 1.21;	
		else
		{
			if ($anyo < 2010)
			{
				$iva = 1.16;		
			}
			else
			{	
				switch($anyo)
				{
					case 2010:
						if ($mes < 7)
							$iva = 1.16;
						else
							$iva = 1.18;	
						break;
					case 2011:
						$iva = 1.18;
						break;
					case 2012:
						if ($mes < 9)
							$iva = 1.18;	
						else
							$iva = 1.21;	
						break;
					default:
						$iva = 1.21;
						break;
				}
			}
		}
		
		return $iva;
	}
    
    
    function formatoDinero($numero)
    {
        return number_format($numero, 2, '.', '');
    }
	
	function mes($num)
	{
		switch($num)
		{
			case 1:
				return 'Enero';
				break;
			case 2:
				return 'Febrero';
				break;
			case 3:
				return 'Marzo';
				break;
			case 4:
				return 'Abril';
				break;
			case 5:
				return 'Mayo';
				break;
			case 6:
				return 'Junio';
				break;
			case 7:
				return 'Julio';
				break;
			case 8:
				return 'Agosto';
				break;
			case 9:
				return 'Septiembre';
				break;
			case 10:
				return 'Octubre';
				break;
			case 11:
				return 'Noviembre';
				break;		
			case 12:
				return 'Diciembre';
				break;
		}
	}

	function listaArt ($stmt)
	{
		global $dbh;
	
		echo '<a href="menuArticulos.php"><h4>Menú artículos</h4></a><h4>Listado de artículos</h4><table class="tabla-listado">';
		
		//cabecera de la tabla
		echo '<tr class="cabeceraTabla"><td><b>Referencia</b></td><td><b>Nombre</b></td><td><b>Precio (Con IVA)</b></td><td><b>Coste</b></td><td><b>Proveedor</b></td><td><b>Unidades</b></td></tr>';
		
		$i=1;
		//para cada artículo añadimos sus propiedades
		foreach ($stmt as $row)
		{
			if ($i%2==0)
				echo '<tr class="filaPar">';
			else
				echo '<tr class="filaImpar">';
				
			$i++;
			
			$referencia = $row['Referencia'];
			
			echo "<td>$referencia</td>";
			echo '<td><a href="datosArticulo.php?articulo='.$referencia.'">'.$row['Nombre'].'</a></td>';
			echo '<td>'.formatoDinero(precioIVA($row['Precio'], null)).'</td>';
			echo '<td>'.$row['Coste'].'</td>';
			
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
			
			
			echo '<td>'.$row['Unidades'].'</td>';
			
			echo '</tr>';
		}
		
		echo '</table>';

	}

	function listaProv($stmt)
	{
		echo '<a href="menuProveedores.php"><h4>Menú proveedores</h4></a><h4>Listado de proveedores</h4><table class="tabla-listado">';
		
		//cabecera de la tabla
		echo '<tr class="cabeceraTabla"><td><b>Nombre</b></td><td><b>Dirección</b></td><td><b>Localidad</b></td><td><b>Teléfono</b></td><td><b>Email</b></td></tr>';
		
		$i=1;
		//para cada artículo añadimos sus propiedades
		foreach ($stmt as $row)
		{
			if ($i%2==0)
				echo '<tr class="filaPar">';
			else
				echo '<tr class="filaImpar">';
				
			$i++;
			
			echo '<td><a href=datosProveedor.php?idProveedor='.$row['IdProveedor'].'>'.$row['Nombre'].'</a></td>';
			
			if ($row['Direccion'] != null)
				echo '<td>'.$row['Direccion'].'</td>';
			else
				echo '<td>---</td>';
			
			if ($row['Localidad']!=null)
				echo '<td>'.$row['Localidad'].'</td>';
			else
				echo '<td>---</td>';
				
			if ($row['Tlf1']!=0)
				echo '<td>'.$row['Tlf1'].'</td>';
			else
				echo '<td>---</td>';
				
			if ($row['Email']!=null)
				echo '<td>'.$row['Email'].'</td>';
			else
				echo '<td>---</td>';
					
			echo '</tr>';
		}
		
		echo '</table>';
	}		

	function listaCli($stmt)
	{
		echo '<a href="menuClientes.php"><h4>Menú clientes</h4></a><h4>Listado de clientes</h4><table class="tabla-listado">';
		
		//cabecera de la tabla
		echo '<tr class="cabeceraTabla"><td><b>Titulo</b></td><td><b>Nombre</b></td><td><b>Apellidos</b></td><td><b>Teléfono</b></td><td><b>Dirección</b></td><td><b>CP</b></td></tr>';
		
		$i=1;
		//para cada cliente añadimos sus propiedades
		foreach ($stmt as $row)
		{
			if ($i%2==0)
				echo '<tr class="filaPar">';
			else
				echo '<tr class="filaImpar">';
				
			$i++;
			
			if ($row['Nombre']!=null)
				echo '<td>'.$row['Titulo'].'</td>';
			else
				echo '<td>---</td>';
			
			if ($row['Nombre']!=null)
				echo '<td>'.$row['Nombre'].'</td>';
			else
				echo '<td>---</td>';
				
			if ($row['Apellidos']!=null)
				echo '<td><a href=datosCliente.php?cliente='.$row['IdCliente'].'>'.$row['Apellidos'].'</a></td>';
			else
				echo '<td><a href=datosCliente.php?cliente='.$row['IdCliente'].'>---</a></td>';
			
			if ($row['Tlf1']!=0)
				echo '<td>'.$row['Tlf1'].'</td>';
			else
				echo '<td>---</td>';
				
			if ($row['Direccion']!=null)
				echo '<td>'.$row['Direccion'].'</td>';
			else
				echo '<td>---</td>';
				
			if ($row['CP']!=0)
				echo '<td>'.$row['CP'].'</td>';
			else
				echo '<td>---</td>';
					
			echo '</tr>';
		}
		
		echo '</table>';
	}	

	function fechaFormatoLargo ($fecha)
	{
		$m = substr($fecha, 5, 2);
		$a = substr($fecha, 0, 4);
		$d = substr($fecha, 8, 2);

		
		$mes=mes($m);

		$num=strftime("%w",strtotime($fecha));
		
		switch($num)
		{
			case 0:
				$dia='domingo';
				break;
			case 1:
				$dia='lunes';
				break;
			case 2:
				$dia='martes';
				break;
			case 3:
				$dia='miércoles';
				break;
			case 4:
				$dia='jueves';
				break;
			case 5:
				$dia='viernes';
				break;
			case 6:
				$dia='sábado';
				break;
		}	

		$fechaLarga=$dia.', '.$d.' de '.$mes.' de '.$a; 
		return $fechaLarga;
	}		
	
	function esIntangible($referencia)
	{
		return $referencia == 97420001
		    || $referencia == 97420002
		    || $referencia == 97420003
		    || $referencia == 97420004
		    || $referencia == 97420005
		    || $referencia == 97420006
		    || $referencia == 97420007
		    || $referencia == 97420008
		    || $referencia == 97420009
		    || $referencia == 97420010
		    || $referencia == 97420011
		    || $referencia == 97420012
		    || $referencia == 97420013
		    || $referencia == 97420030	
			|| $referencia == 97410001
		    || $referencia == 97410002
		    || $referencia == 97410003
		    || $referencia == 97410004
		    || $referencia == 97410005
		    || $referencia == 97410006
		    || $referencia == 97410007
		    || $referencia == 97410008
		    || $referencia == 97410009
		    || $referencia == 97410010
		    || $referencia == 97410011
		    || $referencia == 97410012
		    || $referencia == 97410013
		    || $referencia == 97410014
		    || $referencia == 97410015
		    || $referencia == 97410020
		    || $referencia == 97410021
		    || $referencia == 97410022
		    || $referencia == 97410023
		    || $referencia == 97410024
			|| $referencia == 97410025
		    || $referencia == 97410026
		    || $referencia == 97400001	
			|| $referencia == 93700001	
			|| $referencia == 97400002
			|| $referencia == 97400003
			|| $referencia == 97400004
			|| $referencia == 97400005
			|| $referencia == 97400006
			|| $referencia == 97400007
			|| $referencia == 97400008
			|| $referencia == 97400009			
			|| $referencia == 97400010
			|| $referencia == 97400011
			|| $referencia == 97400012
			|| $referencia == 97400013
			|| $referencia == 97400014
			|| $referencia == 97400015
			|| $referencia == 97500001
			|| $referencia == 97500002
			|| $referencia == 11100032
			|| $referencia == 11100035
			|| $referencia == 82200003
			|| $referencia == 98200003
			|| $referencia == 98200004
			|| $referencia == 98200005
			|| $referencia == 98200006
			|| $referencia == 98200007
			|| $referencia == 98200008
			|| $referencia == 98200009;
	}
	
function calculaBaseImponible ($Id,$cad){
	global $dbh;
	$b=0;
	if ($cad=='venta'){
		$query="SELECT Precio,Unidades,Descuento FROM lineaventa WHERE IdVenta=$Id";
		$stmt=$dbh->query($query);
		foreach ($stmt as $row){
		
			if ($row['Descuento']==0)
				$b+=$row['Precio']*$row['Unidades'];
			else
				$b+=(($row['Precio']*$row['Unidades'])-(($row['Precio']*$row['Unidades']*$row['Descuento'])/100));
		}
	}
	if ($cad=='presupuesto'){
		$query="SELECT Precio,Unidades,Descuento FROM lineapresupuesto WHERE IdPresupuesto=$Id";
		$stmt=$dbh->query($query);
		foreach ($stmt as $row){
		
			if ($row['Descuento']==0)
				$b+=$row['Precio']*$row['Unidades'];
			else
				$b+=(($row['Precio']*$row['Unidades'])-(($row['Precio']*$row['Unidades']*$row['Descuento'])/100));
		}
	}
	return $b;

}

function eliminaLineaVenta($lineaVenta){

		global $dbh;
		
		//obtenemos el artículo
		$query = "SELECT Referencia FROM lineaventa WHERE IdLineaVenta = $lineaVenta ";
		$stmt = $dbh -> query($query);
		$row = $stmt -> fetch();		
		$Referencia = $row['Referencia'];
		//aumentamos las unidades de ese artículo si no es intangible
		if (!esIntangible($Referencia))
		{
			//obtenemos las unidades actuales
			$query = "SELECT Unidades FROM articulo WHERE Referencia = '$Referencia'";
			$stmt = $dbh -> query($query);
			$row = $stmt -> fetch();		
			$unidades = $row['Unidades'];

			//sumamos las que pertenecían a esta línea de venta
			$query = "SELECT Unidades FROM lineaventa WHERE IdLineaVenta = '$lineaVenta'";
			$stmt = $dbh -> query($query);
			$row = $stmt -> fetch();		
			$unidades += $row['Unidades'];

			//finalmente actualizamos la tabla artículo
			$sql = "UPDATE articulo SET Unidades = $unidades WHERE Referencia = '$Referencia'";
			$dbh -> exec($sql);
		}
		
		//y borramos la linea venta
		$sql = "DELETE FROM lineaventa WHERE IdLineaVenta = $lineaVenta";
		$dbh->exec($sql);
}

function redondea ($num){
		$pt=(round($num*100)/100);
		$cf=($pt*100)%100;
		$ce=floor($pt);
		
		if ($cf<25)
			$cf='00';
		if($cf>=25 && $cf <=50)
			$cf=50;
		if($cf>50 && $cf<=75)
			$cf=5;
		if($cf>75 && $cf<=99){
			$aum=true;
		}
		
		if($aum==true)
			$ret=$ce+1;
		else{
			$ret=$ce.'.'.$cf;
		}
		
		return $ret;

}

function redondeaIVA ($num){

	$ret = round($num*100)/100;

	return $ret;

}

function calculaNumeroPresupuesto ($IdPresupuesto, $fecha){
	global $dbh;

	$anoPresupuesto = substr($fecha,0,4);

	$query2 = "SELECT IdPresupuesto FROM presupuesto WHERE YEAR(Fecha) in ($anoPresupuesto) ORDER BY IdPresupuesto ASC";
	$stmt = $dbh->query($query2);

	$numPresupuesto = 1;
	foreach ($stmt as $row){
		if ($row['IdPresupuesto'] == $IdPresupuesto) {
			break;
		}
		$numPresupuesto++;
	}
	return $numPresupuesto.'/'.$anoPresupuesto;
}

function calculaNumeroFactura ($IdVenta, $fecha){
		global $dbh;
		
		$queryFactura = "SELECT IdFactura FROM factura WHERE IdVenta = $IdVenta";
		$stmtFactura = $dbh -> query($queryFactura);
		if ($stmtFactura->rowCount() == 0) {
			// La factura no se ha emitido
			return "--";
		}
		$factura = $stmtFactura -> fetch();
		$IdFactura = $factura['IdFactura'];
		
		$anoVenta = substr($fecha,0,4);
		
		if ($anoVenta > 2009){
		
			$anoVentaAux = $anoVenta - 1;
			$nuevaFecha = "{$anoVentaAux}-12-31";
			$nuevaFecha = '"'.$nuevaFecha.'"';

			$query2 = "SELECT IdVenta FROM venta WHERE FechaVenta <= $nuevaFecha AND IdCliente is not NULL ORDER BY IdVenta DESC";
			$stmt = $dbh->query($query2);
			$row = $stmt->fetch();

			$ultimaVentaAnoAnterior = $row['IdVenta'];

			if (isset($ultimaVentaAnoAnterior)) {
				$query = "SELECT IdFactura FROM factura WHERE IdVenta = $ultimaVentaAnoAnterior";
				$stmt = $dbh -> query($query);
				$row = $stmt->fetch();

				$ultimaFactura = $row['IdFactura'];

				$numFactura = $IdFactura - $ultimaFactura;
				
				$precioVenta=precioVenta($IdVenta);
				
				//Cálculo del número de factura
				if ($precioVenta<0)
				{
					$numFactura = 'A'.$numFactura;
				}
			} else {
				$numFactura = 1;
			}
			
			return $numFactura.'/'.$anoVenta;
		}
		else{
			return $IdFactura.'/'.$anoVenta;
		}

}

function calculaNumeroFacturaAntiguedad ($IdVenta, $fecha){
		global $dbh;
		
		$queryFactura = "SELECT IdFactura FROM facturaant WHERE IdVenta = $IdVenta";
		$stmtFactura = $dbh -> query($queryFactura);
		if ($stmtFactura->rowCount() == 0) {
			// La factura no se ha emitido
			return "--";
		}
		
		$factura = $stmtFactura -> fetch();
		$IdFactura = $factura['IdFactura'];
		
		$anoVenta = substr($fecha,0,4);
		if ($anoVenta > 2009){
		
			$anoVentaAux = $anoVenta - 1;
			$nuevaFecha = "{$anoVentaAux}-12-31";
			$nuevaFecha = '"'.$nuevaFecha.'"';

			$ant = 'Si';
			$query2 = "SELECT IdVenta FROM venta WHERE FechaVenta <= $nuevaFecha AND IdCliente is not NULL AND Antiguedad = 'Si' ORDER BY IdVenta DESC";
			$stmt = $dbh->query($query2);
			$row = $stmt->fetch();

			$ultimaVentaAnoAnterior = $row['IdVenta'];

			$query = "SELECT IdFactura FROM facturaant WHERE IdVenta = $ultimaVentaAnoAnterior";
			$stmt = $dbh -> query($query);
			$row = $stmt->fetch();

			$ultimaFactura = $row['IdFactura'];
			
			$numFactura = 'M'.( $IdFactura - $ultimaFactura);
			
			$precioVenta=precioVenta($IdVenta);
			
			//Cálculo del número de factura
			if ($precioVenta<0)
			{
				$numFactura = 'A-'.$numFactura;
			}
			
			return $numFactura.'/'.$anoVenta;

		}
		else{
			return 'M'.$IdFactura.'/'.$anoVenta;
		}

}

function calculaNumeroTicket ($idVenta){
	global $dbh;

	$stmtVenta = $dbh->query("SELECT FechaVenta FROM venta WHERE IdVenta=$idVenta");
	$row = $stmtVenta -> fetch();
	$anoVenta = substr($row['FechaVenta'],0,4);

	$stmtTicket = $dbh->query("SELECT IdTicket FROM ticket WHERE IdVenta=$idVenta");
	$row = $stmtTicket -> fetch();
	$idTicket = $row['IdTicket'];

	if (isset($idTicket)) {
		if ($anoVenta < 2024) {
			// Para tickets previos a 2024, seguir numeración correlativa (Solicitado por Blanca)
			return $idTicket;
		} else {
			// A partir de 2024 crear tickets con numeración que resetea por ano
			$stmtNumTicket = $dbh->query("SELECT IdTicket FROM ticket where AnoTicket = $anoVenta ORDER BY IdTicket ASC");
			
			$numTicket = 1;
			foreach ($stmtNumTicket as $row){
				if ($row['IdTicket'] == $idTicket) {
					break;
				}
				$numTicket++;
			}
	
			return $numTicket.'/'.$anoVenta;
		}
	} else {
		return "--";
	}
}

function imprimirCabeceraTicket($tienda){

	if ($tienda == 'PT'){
		echo '<tr><td colspan="3" align="left"><u>PT Decoracion y Antiguedades</u></td></tr>';
		echo '<tr><td colspan="3" align="left"><br /></td></tr>';
		echo '<tr><td colspan="3" align="left">Monte Carmelo 32</td></tr>';
		echo '<tr><td colspan="3" align="left">Sevilla</td></tr>';
		echo '<tr><td colspan="3" align="left">954456956</td></tr>';
		echo '<tr><td colspan="3" align="left">B 91262774A</td></tr>';
	}
	else{
		echo '<tr><td colspan="3" align="left"><u>Becara Sevilla</u></td></tr>';
		echo '<tr><td colspan="3" align="left"><br /></td></tr>';
		echo '<tr><td colspan="3" align="left">Monte Carmelo 29</td></tr>';
		echo '<tr><td colspan="3" align="left">Sevilla</td></tr>';
		echo '<tr><td colspan="3" align="left">954275762</td></tr>';
		echo '<tr><td colspan="3" align="left">B 91262774A</td></tr>';
	}
}

?>