<?php
	
	include_once('inc/header.php');			
	//obtemos las listas de boda
	try 
	{
		$query = "SELECT IdListaBoda, Fecha, IdCliente FROM listaboda ORDER BY IdListaBoda DESC";
		$stmt = $dbh->query($query);
		
		echo '<a href="menuListaBoda.php"><h4>Menú lista boda</h4></a><h4>Listado de listas de boda</h4>';
		
		$mes = 0;
		$i=1;
		
		//para cada lista de boda

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
					echo '<table class="tabla-listado"><tr class="cabeceraTabla"><td><b>Id</b></td><td><b>Fecha</b></td><td><b>Precio total</b></td></tr>';
				}
				
				
				if ($i%2==0)
					echo '<tr class="filaPar">';
				else
					echo '<tr class="filaImpar">';
					
				$i++;
				
				$IdListaBoda = $row['IdListaBoda'];
				
				echo '<td><a href="datosListaBoda.php?listaboda='.$IdListaBoda.'">'.$IdListaBoda.'</a></td>';
				echo '<td>'.$row['Fecha'].'</td>';

				$precio = formatoDinero(precioListaBoda($IdListaBoda));
				echo "<td>$precio</td>";
				
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