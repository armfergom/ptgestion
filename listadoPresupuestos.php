<?php
	
	include_once('inc/header.php');		
	include_once('inc/misc.php');

	//obtemos los presupuestos
	try 
	{
		$query = "SELECT IdPresupuesto, Fecha, IdCliente, IdVenta FROM presupuesto ORDER BY IdPresupuesto DESC";
		$stmt = $dbh->query($query);
		
		echo '<a href="menuPresupuestos.php"><h4>Menú presupuestos</h4></a><h4>Listado de presupuestos</h4>';
		
		$mes = 0;
		$i=1;
		
		//para cada presupuesto

			foreach ($stmt as $row)
			{
				$nuevoMes = substr($row['Fecha'], 5, 2);
				$año = substr($row['Fecha'], 0, 4);
				
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
					echo '<table class="tabla-listado"><tr class="cabeceraTabla"><td><b>Id</b></td><td><b>Fecha</b></td><td><b>Precio total</b></td><td><b>Vendido</b></td><td><b>Número Presupuesto</b></td></tr>';
				}
				
				
				if ($i%2==0)
					echo '<tr class="filaPar">';
				else
					echo '<tr class="filaImpar">';
					
				$i++;
				
				$IdPresupuesto = $row['IdPresupuesto'];
				
				echo '<td><a href="datosPresupuesto.php?presupuesto='.$IdPresupuesto.'">'.$IdPresupuesto.'</a></td>';
				echo '<td>'.$row['Fecha'].'</td>';

				$precio = formatoDinero(precioPresupuesto($IdPresupuesto));
				echo "<td>$precio</td>";
				echo '<td>'.($row['IdVenta']!=NULL?'Sí':'No').'</td>';
				echo '<td>'.calculaNumeroPresupuesto($row['IdPresupuesto'],$row['Fecha']).'</td>';
				
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