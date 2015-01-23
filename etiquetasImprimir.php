<?php

	echo '<head><link rel="stylesheet" type="text/css" href="css/printEt.css" media="print">';
	include_once ('inc/db_connect.php');
	include_once ('inc/misc.php');
	
	//mostramos las etiquetas
	if (isset($_REQUEST['generar']))
	{		
		$i = 1;
		$e = 0;
		
		echo '<div class="dinA4Et"><table class="tabla-etiquetas"><tr>';
		while(isset($_REQUEST["referencia$i"]) && isset($_REQUEST["etiquetas$i"]))
		{	
			$referencia = $_REQUEST["referencia$i"];
			$etiquetas = $_REQUEST["etiquetas$i"];
			if ($referencia != 'null' && $etiquetas != null)
			{			
				for ($j = 0; $j < $etiquetas; $j++)
				{
					//imprimimos esa etiqueta
					echo '<td class="tabla-etiqueta">'.'&nbsp;'.$referencia;
					
					$query = "SELECT Nombre, Precio FROM articulo WHERE Referencia = '$referencia'";
					$stmt = $dbh -> query($query);
					$row = $stmt -> fetch();
					
					echo '<br />';
					
					echo '&nbsp;'.substr($row['Nombre'], 0, 15);
					
					echo '<br />';
					
					echo '&nbsp;'.formatoDinero(precioIVA($row['Precio'],null)).'€';
					
					echo '</td>';
					
					//incrementamos etiqueta
					$e += 1;
					if ($e % 64 == 0)
						echo '</tr><tr class = "margen-especial">';
					else if ($e % 4 == 0)
						echo '</tr><tr>';
				}
			}
			
			$i += 1;
		}
		
		echo '</table></div>';
	} 

	disconnect($dbh);
	
?>