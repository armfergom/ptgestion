<?php
	
	if (isset($_REQUEST['generar']))
	{		
		$i = 1;
		$j = 1;
		$e = 0;
		$p=1;
		
		echo '<script type="text/javascript">';
		
		$enlace = "etiquetasImprimir.php?generar=1";
		
		while(isset($_REQUEST["referencia$i"]) && isset($_REQUEST["etiquetas$i"]))
		{	
			$referencia = $_REQUEST["referencia$i"];
			$etiquetas = $_REQUEST["etiquetas$i"];
			if ($referencia != 'null' && $etiquetas != null)
			{			
				while ($e + $etiquetas > 64)
				{
					$caben = 64 - $e;
					$etiquetas -= $caben;
					$enlace .= "&referencia$j=$referencia&etiquetas$j=$caben";
					$p++;
					echo "window.open ('$enlace','mywindow$p');";
					$j = 1;
					$enlace = "etiquetasImprimir.php?generar=1";
					$e=0;
				}
				if ($e + $etiquetas <= 64)
				{
					$enlace .= "&referencia$j=$referencia&etiquetas$j=$etiquetas";
					$j += 1;
					$e+=$etiquetas;
				}
			}
			$i += 1;
		}
		echo "window.open ('$enlace','mywindow');";
		echo "window.close();";
		echo '</script>';
	}
	
				

?>