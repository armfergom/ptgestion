<?php

	echo '<head><link rel="stylesheet" type="text/css" href="css/printFac.css" media="print"/></head><body>';

	include_once ('inc/db_connect.php');
	include_once ('inc/misc.php');
	
	set_time_limit(0);

	if (isset($_REQUEST['listaBoda']))
	{	
		$listaBoda=$_REQUEST['listaBoda'];
		$cabecera=$_REQUEST['cabecera'];
	
		$query="SELECT * FROM linealistaboda WHERE IdListaBoda = $listaBoda AND Comentario NOT LIKE '%fraccion%' ORDER BY Precio ASC";
		$query2="SELECT * FROM listaboda WHERE IdListaBoda = $listaBoda";
		$stmt = $dbh -> query($query);
		$stmt2 = $dbh -> query($query2);
		$row2 = $stmt2 -> fetch();
		$cliente=$row2['IdCliente'];
                $fecha=$row2['Fecha'];
                $IdListaBoda=$row2['IdListaBoda'];;
		$query3="SELECT * FROM cliente WHERE IdCliente=$cliente";
		$stmt3 = $dbh -> query($query3);
		$row3 = $stmt3 -> fetch();

		foreach ($stmt as $row)
		{	
			$re = $row['Referencia'];
			$stmtOrdenado[$re] = (precioIVA($row['Precio'],$fecha)*$row['Unidades']);
		}
		asort($stmtOrdenado);
		
		$nuevaPag=false;
		$baseImponible=0;
		
		echo '<table class="tabla-factura-arriba">';
				echo '	<tr class="margen-superior"></tr>
					<tr class="datosCliente1"><td class="margen-lateral"></td><td></td><td class="margen-lateral"></td></tr>
					<tr class="datosCliente2"><td class="margen-lateral"></td><td><p class="parrafoCentrado"><u><b>'.$cabecera.'</b></u></p></td><td class="margen-lateral"></td></tr>
					<tr class="datosCliente3"><td class="margen-lateral"></td><td></td><td class="margen-lateral"></td></tr></table>
					<table class="tabla-factura">
					<tr class="alturaNormal"><td class="margen-lateral"></td><td><b>Uds.</b></td><td><b>Ref. Art.</b></td><td><b>Descripción</b></td><td><b>Precio U.</b></td><td class="alineado-derecha"><b>Total</b></td><td class="margen-lateral"></td></tr>';

		foreach ($stmtOrdenado as $re=> $pu)
		{
                        $query="SELECT * FROM linealistaboda WHERE IdListaBoda = $listaBoda AND Referencia=$re AND Comentario NOT LIKE '%fraccion%' ORDER BY Precio ASC";
                        $stmt = $dbh -> query($query);

                        foreach($stmt as $row)
                        {
                                if ($nuevaPag)
                                {
                                        echo '<table class="tabla-factura-arriba">
                                                <tr class="margen-superior"></tr>
                                                <tr class="datosCliente1"><td class="margen-lateral"></td><td class="ancho2"></td><td class="margen-lateral"></td></tr>
                                                <tr class="datosCliente2"><td class="margen-lateral"></td><td></td><td class="margen-lateral"></td></tr>
                                                <tr class="datosCliente3"><td class="margen-lateral"></td><td></td><td class="margen-lateral"></td></tr></table>
                                                <table class="tabla-factura">
                                                <tr class="alturaNormal"><td class="margen-lateral"></td><td><b>Uds.</b></td><td><b>Ref. Art.</b></td><td><b>Descripción</b></td><td><b>Precio U.</b></td><td class="alineado-derecha"><b>Total</b></td><td class="margen-lateral"></td></tr>';

                                        $nuevaPag=false;	
                                        $lineas=0;
                                }

                                $precioUnidad=precioIVA($row['Precio'],$fecha);
                                $unidades=$row['Unidades'];
                                $referencia=$row['Referencia'];
                                $comentario=$row['Comentario'];
                                $query4="SELECT Nombre,length(Imagen) AS tam FROM articulo WHERE Referencia=$referencia";
                                $stmt4 = $dbh -> query($query4);
                                $row4 = $stmt4 -> fetch();
                                $nombre=$row4['Nombre'];
                                $descr=$nombre.' '.$comentario;

                                if ($row4['tam'])
                                {
                                        $lineas+=2;
                                        echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td>'.$unidades.'</td><td>'.$referencia.'</td><td class="descripcion">'.$descr.'</td><td>'.formatoDinero($precioUnidad).' €</td><td class="alineado-derecha">'.formatoDinero($precioUnidad*$unidades).' €</td><td>&nbsp&nbsp&nbsp&nbsp <img class="imagenLista" src="getImage.php?articulo=' . $referencia .'" /></td><td class="margen-lateral"></td></tr>';
                                }
                                else
                                {
                                        $lineas++;
                                        echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td>'.$unidades.'</td><td>'.$referencia.'</td><td class="descripcion">'.$descr.'</td><td>'.formatoDinero($precioUnidad).' €</td><td class="alineado-derecha">'.formatoDinero($precioUnidad*$unidades).' €</td><td></td><td class="margen-lateral"></td></tr>';
                                }

                                if ($lineas >= 8){
                                        echo '</table>';
                                        echo '<table class="tabla-factura-abajo" ><tr class="margen-superior"></tr></table>';
                                        //echo '<br class="saltoPagina"/>';
                                        $nuevaPag=true;
                                }
                                
                                //mostramos las fracciones de este artículo
                                $fracQuery = "SELECT * FROM linealistaboda WHERE IdListaBoda = $listaBoda AND Comentario LIKE '%fraccion $referencia%' ORDER BY Precio ASC";
                                $fracStmt = $dbh->query($fracQuery);
                                foreach ($fracStmt as $fracRow)
                                {
                                        if ($nuevaPag)
                                        {
                                                echo '<table class="tabla-factura-arriba">
                                                        <tr class="margen-superior"></tr>
                                                        <tr class="datosCliente1"><td class="margen-lateral"></td><td class="ancho2"></td><td class="margen-lateral"></td></tr>
                                                        <tr class="datosCliente2"><td class="margen-lateral"></td><td></td><td class="margen-lateral"></td></tr>
                                                        <tr class="datosCliente3"><td class="margen-lateral"></td><td></td><td class="margen-lateral"></td></tr></table>
                                                        <table class="tabla-factura">
                                                        <tr class="alturaNormal"><td class="margen-lateral"></td><td><b>Uds.</b></td><td><b>Ref. Art.</b></td><td><b>Descripción</b></td><td><b>Precio U.</b></td><td class="alineado-derecha"><b>Total</b></td><td class="margen-lateral"></td></tr>';

                                                $nuevaPag=false;	
                                                $lineas=0;
                                        }

                                        $precioUnidad=precioIVA($fracRow['Precio'],$fecha);
                                        $unidades=$fracRow['Unidades'];
                                        $referencia=$fracRow['Referencia'];
                                        $comentario=substr($fracRow['Comentario'],9+strlen($referencia),1000);
                                        $query4="SELECT Nombre,length(Imagen) AS tam FROM articulo WHERE Referencia='$referencia'";
                                        $stmt4 = $dbh -> query($query4);
                                        $row4 = $stmt4 -> fetch();
                                        $nombre=$row4['Nombre'];
                                        $descr=$nombre.' '.$comentario;

                                        if ($row4['tam'])
                                        {
                                                $lineas+=2;
                                                echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td></td><td></td><td class="descripcion">'.$descr.'</td><td>'.formatoDinero($precioUnidad).' €</td><td class="alineado-derecha">'.formatoDinero($precioUnidad*$unidades).' €</td><td>&nbsp&nbsp&nbsp&nbsp <img class="imagenLista" src="getImage.php?articulo=' . $referencia .'" /></td><td class="margen-lateral"></td></tr>';
                                        }
                                        else
                                        {
                                                $lineas++;
                                                echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td></td><td></td><td class="descripcion">'.$descr.'</td><td>'.formatoDinero($precioUnidad).' €</td><td class="alineado-derecha">'.formatoDinero($precioUnidad*$unidades).' €</td><td></td><td class="margen-lateral"></td></tr>';
                                        }

                                        if ($lineas >= 8){
                                                echo '</table>';
                                                echo '<table class="tabla-factura-abajo" ><tr class="margen-superior"></tr></table>';
                                                //echo '<br class="saltoPagina"/>';
                                                $nuevaPag=true;
                                        }
                                }
                            
                        }

		}
        
		while ($lineas <9)
		{
			echo '<tr class="alturaNormal"><td class="margen-lateral"></td><td></td><td></td><td class="descripcion"></td><td></td><td></td><td class="alineado-derecha"></td><td class="margen-lateral"></td></tr>';
			$lineas++;
		}
		echo '</table>';
		echo '<table class="tabla-factura-abajo"><tr class="margen-superior-esp"></tr></table>';
	} 

	disconnect($dbh);
	
?>