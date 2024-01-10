<?php

	echo '<head><link rel="stylesheet" type="text/css" href="css/printPres.css" media="print"/></head><body>';

	include_once ('inc/db_connect.php');
	include_once ('inc/misc.php');
    
    
	if (isset($_REQUEST['presupuesto']))
	{	
		//Obtenemos la informacion del presupuesto que vamos a imprimir. Linea de presupuesto, presupuesto, y cliente.
        $presupuesto=$_REQUEST['presupuesto'];
		$query="SELECT * FROM lineapresupuesto,capitulo,subcapitulo WHERE IdPresupuesto = $presupuesto AND lineapresupuesto.IdCapitulo=capitulo.IdCapitulo AND lineapresupuesto.IdSubcapitulo=subcapitulo.IdSubcapitulo ORDER BY capitulo.OrdenCapitulo ASC, subcapitulo.OrdenSubcapitulo ASC";
		//$query="SELECT * FROM lineapresupuesto WHERE IdPresupuesto = $presupuesto ORDER BY IdCapitulo ASC,IdSubcapitulo ASC";

		$query2="SELECT * FROM presupuesto WHERE IdPresupuesto = $presupuesto";
		$stmt = $dbh -> query($query);
		$stmt2 = $dbh -> query($query2);
		$row2 = $stmt2 -> fetch();
		$anoPres=substr($row2['Fecha'],0,4);
		$idPresupuesto=$row2['IdPresupuesto'];

		$cliente=$row2['IdCliente'];
		$query3="SELECT * FROM cliente WHERE IdCliente=$cliente";
		$stmt3 = $dbh -> query($query3);
		$row3 = $stmt3 -> fetch();
		
		//Según el título del cliente, construiremos el nombre de una forma u otra.
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
		
		$electronica = $_REQUEST['electronica'];

		//Si vamos a imprimir una factura de un presupuesto
        if (isset($_REQUEST['facturaPresupuesto'])){
            $facturaPresupuesto=true;
            $IdVenta=$row2['IdVenta'];
			
			$queryVent="SELECT * FROM venta WHERE IdVenta = $IdVenta";
			$stmtVent = $dbh -> query($queryVent);
			$rowVent = $stmtVent -> fetch();
			$fecha = $rowVent['FechaVenta'];
			$anoVenta = substr($fecha,0,4);
			$ant = $rowVent['Antiguedad']; 
			
			if ($ant == 'Si')
				$queryFac="SELECT * FROM facturaant WHERE IdVenta=$IdVenta";
			else
				$queryFac="SELECT * FROM factura WHERE IdVenta=$IdVenta";
				
            $stmtFac = $dbh -> query($queryFac);
			//Si ya se había hecho una factura, tomamos los datos.
            if (($stmtFac -> rowcount()) > 0){
                $rowFac = $stmtFac -> fetch();
                $IdFactura=$rowFac['IdFactura'];
				if ($ant == 'Si')
					$IdFactura = calculaNumeroFacturaAntiguedad ($IdVenta,$fecha);
				else
					$IdFactura = calculaNumeroFactura($IdVenta,$fecha);
            }
			//Si no se había hecho la factura, la damos de alta.
            else{
				if ($ant == 'Si')
					$sqlFac="INSERT INTO facturaant (IdVenta) VALUES ($IdVenta)";
				else
					$sqlFac="INSERT INTO factura (IdVenta) VALUES ($IdVenta)";

                $dbh->exec($sqlFac);
                if ($ant == 'Si')
					$IdFactura = calculaNumeroFacturaAntiguedad ($IdVenta,$fecha);
				else
					$IdFactura = calculaNumeroFactura($IdVenta,$fecha);
            }
        }
		//si no vamos a imprimir el presupuesto (No la factura del presupuesto).
        else{
            $facturaPresupuesto=false;
			$fecha = $row2['Fecha'];
        }
		
		$query5="SELECT * FROM lineapresupuesto WHERE IdPresupuesto=$presupuesto AND Descuento <> 0";
		$stmt5 = $dbh -> query($query5);
		
		//Vemos si alguna parte del presupuesto tiene descuento.
		if (($stmt5 -> rowcount())!=0)
			$conDesc=true;
		else
			$conDesc=false;
			
		//Vemos si el CP y el NIF existen. Si no no se mostrarán.	
		if ($row3['CP']==0)
			$CP=null;
		else
			$CP=$row3['CP'];
			
		if ($row3['NIF'] == null)
			$NIF=null;
		else
			$NIF='NIF '.$row3['NIF'];
    
		$nuevaPag=true;
		$baseImponible=0;
		$capituloTemporal=false;
        $subcapituloTemporal=false;
		$numPresupuesto=calculaNumeroPresupuesto($idPresupuesto,$fecha);
        
		//Recorremos los datos de la linea de presupuesto para ir mostrándolos.
		foreach ($stmt as $row){
        
			//Si es una nueva página tendremos que mostrar otra vez el encabezado.
			if ($nuevaPag){
			    if ($electronica == 'no'){
           			echo '<table class="tabla-factura-arriba">
					<tr class="margen-superior"></tr>';
           		}
           		else{
           			echo '<table class="tabla-imagen-cabecera"><tr><td><img class ="imagenFactura" src="./Imagenes/cabeceraFacturaElectronica.png" alt="La imagen no se ha podido cargar"></img></td><tr></table>';
					echo '<table class="tabla-factura-arriba-electronica">';
           		}

				//Cabecera. Distinta si es una factura y si es un presupuesto.
               	if ($facturaPresupuesto){                	
                   	echo ' 	<tr class="datosCliente1"><td class="margen-lateral"></td><td class="ancho2">'.$cadenaTituloEtc.'</td><td class="alineado-derecha"> FRA '.$IdFactura.'</td><td class="margen-lateral"></td></tr>';
					echo '<tr class="datosCliente2"><td class="margen-lateral"></td><td colspan="2">'.$row3['Direccion'].' </td><td class="margen-lateral"></td></tr>
					<tr class="datosCliente3"><td class="margen-lateral"></td><td colspan="2">'.$row3['Localidad'].' '.$CP.'&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp '.$NIF.'</td><td class="margen-lateral"></td></tr></table>
					<table class="tabla-factura">';
				}
				else{
                   	echo ' 	<tr class="datosCliente1"><td class="margen-lateral"></td><td class="ancho2">'.$cadenaTituloEtc.'</td><td class="alineado-derecha"> P-'.$numPresupuesto.'</td><td class="margen-lateral"></td></tr>';
					echo '<tr class="datosCliente2"><td class="margen-lateral"></td><td colspan="2"></td><td class="margen-lateral"></td></tr>
					<tr class="datosCliente3"><td class="margen-lateral"></td><td colspan="2"></td><td class="margen-lateral"></td></tr></table>
					<table class="tabla-factura">';
				}
					
				
				//Tipos de datos. Distinto si hay algún artículo con descuento.
				if ($conDesc)
					echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td><b>Ref. Art.</b></td><td><b>Descripción</b></td><td><b>Precio unidad</b></td><td><b>Uds.</b></td><td><b>Desc.</b></td><td class="alineado-derecha"><b>Total</b></td><td class="margen-lateral"></td></tr>';
				else
					echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td><b>Ref. Art.</b></td><td><b>Descripción</b></td><td><b>Precio unidad</b></td><td><b>Uds.</b></td><td class="alineado-derecha"><b>Total</b></td><td class="margen-lateral"></td></tr>';
						
                    $lineas=1;

					//Si antes de terminar la página anterior faltaba un capítulo nuevo por escribir.
                    if ($continuarNuevoC){
						if (!$conDesc)
							echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td colspan="5" class="alineado-izquierda"><b>CAP. '.$nombreCapitulo.'</b></td><td class="margen-lateral"></td></tr>';
                        else
							echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td colspan="6" class="alineado-izquierda"><b>CAP. '.$nombreCapitulo.'</b></td><td class="margen-lateral"></td></tr>';
						
						$lineas++;
                        $capituloNuevo=false;
						$continuarNuevoC=false;
                    }
					
					//Si antes de terminar la página anterior faltaba un capítulo nuevo por escribir.
                    if ($continuarNuevoS){
						if (!$conDesc)
							echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td colspan="5" class="alineado-izquierda"><b>'.$nombreSubcapitulo.'</b></td><td class="margen-lateral"></td></tr>';
                        else
							echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td colspan="6" class="alineado-izquierda"><b>'.$nombreSubcapitulo.'</b></td><td class="margen-lateral"></td></tr>';
						
						$lineas++;
                        $subcapituloNuevo=false;
						$continuarNuevoS=false;
                    }
					//Si antes de terminar la página anterior faltaba un artículo por escribir.
                    if ($continuarNormal){
						if (!$conDesc)
							echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td>'.$referencia.'</td><td class="descripcion">'.$descr.'</td><td>'.formatoDinero($precioUnidad).' €</td><td>'.$unidades.'</td><td class="alineado-derecha">'.formatoDinero($precioUnidad*$unidades).' €</td><td class="margen-lateral"></td></tr>';
                        else{
							if ($descuento == 0)
								echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td>'.$referencia.'</td><td class="descripcion">'.$descr.'</td><td>'.formatoDinero($precioUnidad).' €</td><td>'.$unidades.'</td><td></td><td class="alineado-derecha">'.formatoDinero($precioUnidad*$unidades).' €</td><td class="margen-lateral"></td></tr>';
							else
								echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td>'.$referencia.'</td><td class="descripcion">'.$descr.'</td><td>'.formatoDinero($precioUnidad).' €</td><td>'.$unidades.'</td><td>'.$descuento.' %</td><td class="alineado-derecha">'.formatoDinero((($precioUnidad*$unidades)-(($precioUnidad*$unidades*$descuento)/100))).' €</td><td class="margen-lateral"></td></tr>';	
						}
						$lineas++;
                        $totalCapitulo+=($precioUnidad*$unidades)-(($precioUnidad*$unidades*$descuento)/100);
						$continuarNormal=false;
                    }
					$nuevaPag=false;	
			}		
			//Si no hemos empezado a escribir.
            if(!$capituloTemporal){
                $capituloTemporal=$row['IdCapitulo'];
                $sqlNombreC="SELECT Nombre FROM capitulo WHERE IdCapitulo=$capituloTemporal";
                $stmtNombreC=$dbh->query($sqlNombreC);
                $rowNombreC=$stmtNombreC->fetch();
                $nombreCapitulo=$rowNombreC['Nombre'];
                $capituloNuevo=true;
            }
            else{
				//Si el capítulo nuevo es distinto de el que estabamos tratando, el capítulo que estabamos tratando termina y empieza otro.
                if($capituloTemporal != $row['IdCapitulo']){
                    $capituloTemporal2=$nombreCapitulo;
                    $capituloTemporal=$row['IdCapitulo'];
                    $sqlNombreC="SELECT Nombre FROM capitulo WHERE IdCapitulo=$capituloTemporal";
                    $stmtNombreC=$dbh->query($sqlNombreC);
                    $rowNombreC=$stmtNombreC->fetch();
                    $nombreCapitulo=$rowNombreC['Nombre'];
                    $finalCapitulo=true;
                    $capituloNuevo=true;
                }
            }
            //Si es el primer subcapitulo, o cambiamos de capitulo o estamos en un capitulo nuevo, habrá un cambio de subcapítulo.
            if(!$subcapituloTemporal || $subcapituloTemporal != $row['IdSubcapitulo'] || $capituloNuevo){   
                $subcapituloTemporal=$row['IdSubcapitulo'];
                $sqlNombreS="SELECT Nombre FROM subcapitulo WHERE IdSubcapitulo=$subcapituloTemporal";
                $stmtNombreS=$dbh->query($sqlNombreS);
                $rowNombreS=$stmtNombreS->fetch();
                $nombreSubcapitulo=$rowNombreS['Nombre'];
                $subcapituloNuevo=true;
            }
				//Metemos datos de las consultas en variables.
				$precioUnidad=$row['Precio'];
				$unidades=$row['Unidades'];
				$descuento=$row['Descuento'];
				$baseImponible+=(($precioUnidad*$unidades)-(($precioUnidad*$unidades*$descuento)/100));
				$referencia=$row['Referencia'];
				$comentario=$row['Comentario'];
				$query4="SELECT Nombre FROM articulo WHERE Referencia='$referencia'";
				$stmt4 = $dbh -> query($query4);
				$row4 = $stmt4 -> fetch();
				$nombre=$row4['Nombre'];
				
				$descr=$nombre.' '.$comentario;
				
				//Si es el final del capitulo y no es factura de un presupuesto,
				//Escribimos el total del capítulo.
                if (!$facturaPresupuesto){
                    if ($finalCapitulo){
						if (!$conDesc)
							echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td colspan="4" class="alineado-derecha"><b>TOTAL '.$capituloTemporal2.'</b><br/><b>'.cuotaIVA($fecha).'</b></td><td class="alineado-derecha">'.formatoDinero(redondea($totalCapitulo)).' € <br />'.formatoDinero(impuestoIVA($totalCapitulo, $fecha)) .' €</td><td class="margen-lateral"></td></tr>';
                        else
							echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td colspan="5" class="alineado-derecha"><b>TOTAL '.$capituloTemporal2.'</b><br/><b>'.cuotaIVA($fecha).'</b></td><td class="alineado-derecha">'.formatoDinero(redondea($totalCapitulo)).' € <br />'.formatoDinero(impuestoIVA($totalCapitulo, $fecha)) .' €</td><td class="margen-lateral"></td></tr>';

						$lineas++;
                        $finalCapitulo=false;
						$totalCapitulo=0;
                    }
                }
				
                //Escribimos nuevo capitulo si lo hay
				//Si lineas == 8, no cabe, se hará en la siguiente vuelta
                if( $lineas >= 8 && $capituloNuevo){
                    $continuarNuevoC=true;
					$nuevaPag=true;
					$continuarNuevoS=true;
					$continuarNormal=true;
					continue;
                }
                else{
                    if ($capituloNuevo){
						if (!$conDesc)
							echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td colspan="5" class="alineado-izquierda"><b>CAP. '.$nombreCapitulo.'</b></td><td class="margen-lateral"></td></tr>';
                        else
							echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td colspan="6" class="alineado-izquierda"><b>CAP. '.$nombreCapitulo.'</b></td><td class="margen-lateral"></td></tr>';
                        
						$lineas++;
                        $capituloNuevo=false;
                    }
                }
                
                if( $lineas >= 9  && $subcapituloNuevo){
                    $continuarNuevoS=true;
					$continuarNormal=true;
					$nuevaPag=true;
					continue;
                }
                else{
                    if ($subcapituloNuevo){
						if (!$conDesc)
							echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td colspan="5" class="alineado-izquierda"><b>'.$nombreSubcapitulo.'</b></td><td class="margen-lateral"></td></tr>';
                        else
							echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td colspan="6" class="alineado-izquierda"><b>'.$nombreSubcapitulo.'</b></td><td class="margen-lateral"></td></tr>';
                        $lineas++;
                        $subcapituloNuevo=false;
                    }
                }

                
				if ($lineas == 10){
                    $continuarNormal=true;
					echo '</table>';
					if (!$facturaPresupuesto)
						echo '<table class="tabla-factura-abajo" ><tr class="margen-superior"></tr></table>';
					else
						echo '<table class="tabla-factura-abajo"><tr class="margen-superior-esp"><td class="margen-lateral"></td><td><b>Sevilla</b>, '.fechaFormatoLargo($row2['Fecha']).'</td><td class="margen-lateral"></td></tr></table>';

					$nuevaPag=true;
				}
                else{
					if (!$conDesc)
						echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td>'.$referencia.'</td><td class="descripcion">'.$descr.'</td><td>'.formatoDinero($precioUnidad).' €</td><td>'.$unidades.'</td><td class="alineado-derecha">'.formatoDinero($precioUnidad*$unidades).' €</td><td class="margen-lateral"></td></tr>';
                    else{
						if ($descuento == 0)
							echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td>'.$referencia.'</td><td class="descripcion">'.$descr.'</td><td>'.formatoDinero($precioUnidad).' €</td><td>'.$unidades.'</td><td></td><td class="alineado-derecha">'.formatoDinero($precioUnidad*$unidades).' €</td><td class="margen-lateral"></td></tr>';
						else
							echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td>'.$referencia.'</td><td class="descripcion">'.$descr.'</td><td>'.formatoDinero($precioUnidad).' €</td><td>'.$unidades.'</td><td>'.$descuento.' %</td><td class="alineado-derecha">'.formatoDinero(redondea(($precioUnidad*$unidades)-(($precioUnidad*$unidades*$descuento)/100))).' €</td><td class="margen-lateral"></td></tr>';	
					}                    
					$lineas++;
					if ($lineas==10)
						$nuevaPag=true;
                    $totalCapitulo+=($precioUnidad*$unidades)-(($precioUnidad*$unidades*$descuento)/100);
                }
		}
		
		//Si queda un ultimo articulo por escribir...
			if ($nuevaPag){
			    if ($electronica == 'no'){
           			echo '<table class="tabla-factura-arriba">
					<tr class="margen-superior"></tr>';
           		}
           		else{
           			echo '<table class="tabla-imagen-cabecera"><tr><td><img class ="imagenFactura" src="./Imagenes/cabeceraFacturaElectronica.png" alt="La imagen no se ha podido cargar"></img></td><tr></table>';
					echo '<table class="tabla-factura-arriba-electronica">';
           		}
				//Cabecera. Distinta si es una factura y si es un presupuesto.
               	if ($facturaPresupuesto){
                   	echo ' 	<tr class="datosCliente1"><td class="margen-lateral"></td><td class="ancho2">'.$cadenaTituloEtc.'</td><td class="alineado-derecha"> FRA '.$IdFactura.'</td><td class="margen-lateral"></td></tr>';
					echo '<tr class="datosCliente2"><td class="margen-lateral"></td><td colspan="2">'.$row3['Direccion'].' </td><td class="margen-lateral"></td></tr>
					<tr class="datosCliente3"><td class="margen-lateral"></td><td colspan="2">'.$row3['Localidad'].' '.$CP.'&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp '.$NIF.'</td><td class="margen-lateral"></td></tr></table>
					<table class="tabla-factura">';
				}
				else{
                   	echo ' 	<tr class="datosCliente1"><td class="margen-lateral"></td><td class="ancho2">'.$cadenaTituloEtc.'</td><td class="alineado-derecha"> P-'.$numPresupuesto.'</td><td class="margen-lateral"></td></tr>';
					echo '<tr class="datosCliente2"><td class="margen-lateral"></td><td colspan="2"></td><td class="margen-lateral"></td></tr>
					<tr class="datosCliente3"><td class="margen-lateral"></td><td colspan="2"></td><td class="margen-lateral"></td></tr></table>
					<table class="tabla-factura">';
				}
				
				//Tipos de datos. Distinto si hay algún artículo con descuento.
				if ($conDesc)
					echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td><b>Ref. Art.</b></td><td><b>Descripción</b></td><td><b>Precio unidad</b></td><td><b>Uds.</b></td><td><b>Desc.</b></td><td class="alineado-derecha"><b>Total</b></td><td class="margen-lateral"></td></tr>';
				else
					echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td><b>Ref. Art.</b></td><td><b>Descripción</b></td><td><b>Precio unidad</b></td><td><b>Uds.</b></td><td class="alineado-derecha"><b>Total</b></td><td class="margen-lateral"></td></tr>';
						
                    $lineas=1;
			}
					//Si antes de terminar la página anterior faltaba un capítulo nuevo por escribir.
                    if ($continuarNuevoC){
						if (!$conDesc)
							echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td colspan="5" class="alineado-izquierda"><b>CAP. '.$nombreCapitulo.'</b></td><td class="margen-lateral"></td></tr>';
                        else
							echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td colspan="6" class="alineado-izquierda"><b>CAP. '.$nombreCapitulo.'</b></td><td class="margen-lateral"></td></tr>';
						
						$lineas++;
                        $capituloNuevo=false;
						$continuarNuevoC=false;
                    }
					
					//Si antes de terminar la página anterior faltaba un capítulo nuevo por escribir.
                    if ($continuarNuevoS){
						if (!$conDesc)
							echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td colspan="5" class="alineado-izquierda"><b>'.$nombreSubcapitulo.'</b></td><td class="margen-lateral"></td></tr>';
                        else
							echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td colspan="6" class="alineado-izquierda"><b>'.$nombreSubcapitulo.'</b></td><td class="margen-lateral"></td></tr>';
						
						$lineas++;
                        $subcapituloNuevo=false;
						$continuarNuevoS=false;
                    }
					//Si antes de terminar la página anterior faltaba un artículo por escribir.
                    if ($continuarNormal){
						if (!$conDesc)
							echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td>'.$referencia.'</td><td class="descripcion">'.$descr.'</td><td>'.formatoDinero($precioUnidad).' €</td><td>'.$unidades.'</td><td class="alineado-derecha">'.formatoDinero($precioUnidad*$unidades).' €</td><td class="margen-lateral"></td></tr>';
                        else{
							if ($descuento == 0)
								echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td>'.$referencia.'</td><td class="descripcion">'.$descr.'</td><td>'.formatoDinero($precioUnidad).' €</td><td>'.$unidades.'</td><td></td><td class="alineado-derecha">'.formatoDinero($precioUnidad*$unidades).' €</td><td class="margen-lateral"></td></tr>';
							else
								echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td>'.$referencia.'</td><td class="descripcion">'.$descr.'</td><td>'.formatoDinero($precioUnidad).' €</td><td>'.$unidades.'</td><td>'.$descuento.' %</td><td class="alineado-derecha">'.formatoDinero((($precioUnidad*$unidades)-(($precioUnidad*$unidades*$descuento)/100))).' €</td><td class="margen-lateral"></td></tr>';	
						}
						$lineas++;
                        $totalCapitulo+=($precioUnidad*$unidades)-(($precioUnidad*$unidades*$descuento)/100);
						$continuarNormal=false;
                    }
					$nuevaPag=false;	
		
        //a partir de aquí ya no hay más artículos que escribir.
		$capituloTemporal2=$nombreCapitulo;
         if (!$facturaPresupuesto){
                if($lineas <10){
					if (!$conDesc)
						echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td colspan="4" class="alineado-derecha"><b>TOTAL '.$capituloTemporal2.'</b><br/><b>'.cuotaIVA($fecha).'</b></td><td class="alineado-derecha">'.formatoDinero(redondea($totalCapitulo)).' € <br />'.formatoDinero(impuestoIVA($totalCapitulo, $fecha)).' €</td><td class="margen-lateral"></td></tr>';
                    else
						echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td colspan="5" class="alineado-derecha"><b>TOTAL '.$capituloTemporal2.'</b><br/><b>'.cuotaIVA($fecha).'</b></td><td class="alineado-derecha">'.formatoDinero(redondea($totalCapitulo)).' € <br />'.formatoDinero(impuestoIVA($totalCapitulo, $fecha)).' €</td><td class="margen-lateral"></td></tr>';
                    $lineas++;
                    $totalCapitulo=0;
                }
				else{
					if ($electronica == 'no'){
           				echo '<table class="tabla-factura-arriba">
						<tr class="margen-superior"></tr>';
           			}
           			else{
           				echo '<table class="tabla-imagen-cabecera"><tr><td><img class ="imagenFactura" src="./Imagenes/cabeceraFacturaElectronica.png" alt="La imagen no se ha podido cargar"></img></td><tr></table>';
						echo '<table class="tabla-factura-arriba-electronica">';
          	 		}
					//Cabecera. Distinta si es una factura y si es un presupuesto.
               		if ($facturaPresupuesto){
               	    	echo ' 	<tr class="datosCliente1"><td class="margen-lateral"></td><td class="ancho2">'.$cadenaTituloEtc.'</td><td class="alineado-derecha"> FRA '.$IdFactura.'</td><td class="margen-lateral"></td></tr>';
						echo '<tr class="datosCliente2"><td class="margen-lateral"></td><td colspan="2">'.$row3['Direccion'].' </td><td class="margen-lateral"></td></tr>
						<tr class="datosCliente3"><td class="margen-lateral"></td><td colspan="2">'.$row3['Localidad'].' '.$CP.'&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp '.$NIF.'</td><td class="margen-lateral"></td></tr></table>
						<table class="tabla-factura">';
					}
					else{
               	    	echo ' 	<tr class="datosCliente1"><td class="margen-lateral"></td><td class="ancho2">'.$cadenaTituloEtc.'</td><td class="alineado-derecha"> P-'.$numPresupuesto.'</td><td class="margen-lateral"></td></tr>';
						echo '<tr class="datosCliente2"><td class="margen-lateral"></td><td colspan="2"></td><td class="margen-lateral"></td></tr>
						<tr class="datosCliente3"><td class="margen-lateral"></td><td colspan="2"></td><td class="margen-lateral"></td></tr></table>
						<table class="tabla-factura">';
					}
					
					if ($conDesc)
						echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td><b>Ref. Art.</b></td><td><b>Descripción</b></td><td><b>Precio unidad</b></td><td><b>Uds.</b></td><td><b>Desc.</b></td><td class="alineado-derecha"><b>Total</b></td><td class="margen-lateral"></td></tr>';
					else
						echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td><b>Ref. Art.</b></td><td><b>Descripción</b></td><td><b>Precio unidad</b></td><td><b>Uds.</b></td><td class="alineado-derecha"><b>Total</b></td><td class="margen-lateral"></td></tr>';
					//
					if ($continuarNuevoC){
						if (!$conDesc)
							echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td colspan="5" class="alineado-izquierda"><b>CAP. '.$nombreCapitulo.'</b></td><td class="margen-lateral"></td></tr>';
                        else
							echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td colspan="6" class="alineado-izquierda"><b>CAP. '.$nombreCapitulo.'</b></td><td class="margen-lateral"></td></tr>';
						
						$lineas++;
                        $capituloNuevo=false;
						$continuarNuevoC=false;
                    }
                    if ($continuarNuevoS){
						if (!$conDesc)
							echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td colspan="5" class="alineado-izquierda"><b>'.$nombreSubcapitulo.'</b></td><td class="margen-lateral"></td></tr>';
                        else
							echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td colspan="6" class="alineado-izquierda"><b>'.$nombreSubcapitulo.'</b></td><td class="margen-lateral"></td></tr>';
						
						$lineas++;
                        $subcapituloNuevo=false;
						$continuarNuevoS=false;
                    }
					if ($continuarNormal){
						if (!$conDesc)
							echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td>'.$referencia.'</td><td class="descripcion">'.$descr.'</td><td>'.formatoDinero($precioUnidad).' €</td><td>'.$unidades.'</td><td class="alineado-derecha">'.formatoDinero($precioUnidad*$unidades).' €</td><td class="margen-lateral"></td></tr>';
                        else{
							if ($descuento == 0)
								echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td>'.$referencia.'</td><td class="descripcion">'.$descr.'</td><td>'.formatoDinero($precioUnidad).' €</td><td>'.$unidades.'</td><td></td><td class="alineado-derecha">'.formatoDinero($precioUnidad*$unidades).' €</td><td class="margen-lateral"></td></tr>';
							else
								echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td>'.$referencia.'</td><td class="descripcion">'.$descr.'</td><td>'.formatoDinero($precioUnidad).' €</td><td>'.$unidades.'</td><td>'.$descuento.' %</td><td class="alineado-derecha">'.formatoDinero(redondea(($precioUnidad*$unidades)-(($precioUnidad*$unidades*$descuento)/100))).' €</td><td class="margen-lateral"></td></tr>';	
						}
						$lineas++;
                        $totalCapitulo+=($precioUnidad*$unidades)-(($precioUnidad*$unidades*$descuento)/100);
						$continuarNormal=false;
                    }
					//
					$lineas=2;
					
					if (!$conDesc)
						echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td colspan="4" class="alineado-derecha"><b>TOTAL '.$capituloTemporal2.'</b><br/><b>'.cuotaIVA($fecha).'</b></td><td class="alineado-derecha">'.formatoDinero(redondea($totalCapitulo)).' € <br />'.formatoDinero(impuestoIVA($totalCapitulo, $fecha)).' €</td><td class="margen-lateral"></td></tr>';
                    else
						echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td colspan="5" class="alineado-derecha"><b>TOTAL '.$capituloTemporal2.'</b><br/><b>'.cuotaIVA($fecha).'</b></td><td class="alineado-derecha">'.formatoDinero(redondea($totalCapitulo)).' € <br />'.formatoDinero(impuestoIVA($totalCapitulo, $fecha)).' €</td><td class="margen-lateral"></td></tr>';
                    $lineas++;
					$totalCapitulo=0;

				}
        }
                

		if ($lineas < 10){
			if (!$conDesc)
				echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td></td><td class="descripcion"></td><td>SUMA TOTAL BASE IMPONIBLE<br />'.cuotaSumaIVA($fecha).'<br /><b>TOTAL I.V.A INCLUIDO</b></td><td></td><td class="alineado-derecha">'.formatoDinero($baseImponible).' €<br/>'. formatoDinero(impuestoIVA($baseImponible, $fecha)) .' €<br/>'.formatoDinero(precioPresupuesto($presupuesto)).' €</td><td class="margen-lateral"></td></tr>';				
			else
				echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td></td><td class="descripcion"></td><td colspan=2>SUMA TOTAL BASE IMPONIBLE<br />'.cuotaSumaIVA($fecha).'<br /><b>TOTAL I.V.A INCLUIDO</b></td><td colspan=2 class="alineado-derecha">'.formatoDinero($baseImponible).' €<br/>'.formatoDinero(impuestoIVA($baseImponible, $fecha)) .' €<br/>'.formatoDinero(precioPresupuesto($presupuesto)).' €</td><td class="margen-lateral"></td></tr>';				

		}
		else{
		    if ($electronica == 'no'){
           		echo '<table class="tabla-factura-arriba">
				<tr class="margen-superior"></tr>';
           	}
           	else{
           		echo '<table class="tabla-imagen-cabecera"><tr><td><img class ="imagenFactura" src="./Imagenes/cabeceraFacturaElectronica.png" alt="La imagen no se ha podido cargar"></img></td><tr></table>';
				echo '<table class="tabla-factura-arriba-electronica">';
           	}
			//Cabecera. Distinta si es una factura y si es un presupuesto.
            if ($facturaPresupuesto){
                echo ' 	<tr class="datosCliente1"><td class="margen-lateral"></td><td class="ancho2">'.$cadenaTituloEtc.'</td><td class="alineado-derecha"> FRA '.$IdFactura.'</td><td class="margen-lateral"></td></tr>';
				echo '<tr class="datosCliente2"><td class="margen-lateral"></td><td colspan="2">'.$row3['Direccion'].' </td><td class="margen-lateral"></td></tr>
				<tr class="datosCliente3"><td class="margen-lateral"></td><td colspan="2">'.$row3['Localidad'].' '.$CP.'&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp '.$NIF.'</td><td class="margen-lateral"></td></tr></table>
				<table class="tabla-factura">';
			}
			else{
              	echo ' 	<tr class="datosCliente1"><td class="margen-lateral"></td><td class="ancho2">'.$cadenaTituloEtc.'</td><td class="alineado-derecha"> P-'.$numPresupuesto.'</td><td class="margen-lateral"></td></tr>';
				echo '<tr class="datosCliente2"><td class="margen-lateral"></td><td colspan="2"></td><td class="margen-lateral"></td></tr>
				<tr class="datosCliente3"><td class="margen-lateral"></td><td colspan="2"></td><td class="margen-lateral"></td></tr></table>
				<table class="tabla-factura">';
			}
				

			if (!$conDesc)
				echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td></td><td class="descripcion"></td><td>SUMA TOTAL BASE IMPONIBLE<br />'.cuotaSumaIVA($fecha).'<br /><b>TOTAL I.V.A INCLUIDO</b></td><td></td><td class="alineado-derecha">'.formatoDinero($baseImponible).' €<br/>'. formatoDinero(impuestoIVA($baseImponible, $fecha)) .' €<br/>'.formatoDinero(precioPresupuesto($presupuesto)).' €</td><td class="margen-lateral"></td></tr>';				
			else
				echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td></td><td class="descripcion"></td><td colspan=2 >SUMA TOTAL BASE IMPONIBLE<br />'.cuotaSumaIVA($fecha).'<br /><b>TOTAL I.V.A INCLUIDO</b></td><td colspan=2 class="alineado-derecha">'.formatoDinero($baseImponible).' €<br/>'. formatoDinero(impuestoIVA($baseImponible, $fecha)) .' €<br/>'.formatoDinero(precioPresupuesto($presupuesto)).' €</td><td class="margen-lateral"></td></tr>';				
				
				$lineas=1;
		}        
        
        
		while ($lineas <10){
			if (!$conDesc)
				echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td></td><td class="descripcion"></td><td></td><td></td><td class="alineado-derecha"></td><td class="margen-lateral"></td></tr>';
			else
				echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td></td><td class="descripcion"></td><td></td><td></td><td></td><td class="alineado-derecha"></td><td class="margen-lateral"></td></tr>';

			$lineas++;
		}
		
		echo '</table>';
		echo '<table class="tabla-factura-abajo"><tr class="margen-superior-esp"><td class="margen-lateral"></td><td><b>Sevilla</b>, '.fechaFormatoLargo($fecha).'</td><td class="margen-lateral"></td></tr></table>';
	} 

	disconnect($dbh);
	
?>