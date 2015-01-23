<?php
	
	include_once('inc/header.php');
		
	$cadenaDiv1 = '<div id=';
	$cadenaDiv2 = '></div>';
	
	echo '<script type="text/javascript">
			var numero = 1;
			function otroArticulo()
			{
				var artConNum = "articulo"+numero;
				var artConNumMasUno = "articulo"+(numero+1);
				var cadenaDiv = "'.$cadenaDiv1.'"+artConNumMasUno+"'.$cadenaDiv2.'"; 					
				document.getElementById("articulo" + numero).innerHTML += texto1 + numero + texto2 + numero + texto3 + cadenaDiv;								
				numero += 1;
			}
		  </script>';
	
	//valida el formulario
	function validaFormulario()
	{
		$errores = '';
		
		$articulos = 0;
		$i = 1;
		while(isset($_REQUEST["referencia$i"]) && isset($_REQUEST["etiquetas$i"]))
		{				
			$referencia = $_REQUEST["referencia$i"];
			$etiquetas = $_REQUEST["etiquetas$i"];
					
			//////////////////
			if ($referencia == 'null' xor $etiquetas == null)
				$errores .= '<li>La referencia y las etiquetas son campos obligatorios.</li>';
			else
			{
				if ($etiquetas != null)
				{
					//ver que las etiquetas sea un número positivo
					if (!preg_match('/^[1-9][0-9]*$/',$etiquetas))
						$errores .= "<li>etiquetas debe ser un número positivo.</li>";
					else
						$articulos += 1;
				}
			}
			
			//////////////////
						
			$i += 1;
		}
		
		if ($articulos == 0)
			$errores .= "<li>Debe seleccionar al menos un artículo.</li>";

		return $errores;
	}

	//muestra el formulario de altas de compras
	function formularioEtiquetas()
	{
		global $dbh;
		
		echo '<a href="menuArticulos.php"><h4>Menú artículos</h4></a>
			<h4>Gestión de etiquetas</h4>
			<form method="post" action="distribuirEtiquetas.php" target="_new"> 
				<table class="tabla-centrada">
					<tr>
						<td class="centrado"><label for="referencia"><b>Referencia</b></label></td>
						<td class="centrado"><label for="etiquetas"><b>Etiquetas</b></label></td>
					</tr>';
				
			echo '<script type="text/javascript">
					var texto1 = "<tr><td class=centrado><select name=referencia";
					var texto2= ">';
					
					//obtemos los artículos de la tabla artículo
					try {
							$query = "SELECT Referencia FROM articulo ORDER BY Referencia ASC";
							$stmt = $dbh->query($query);							
							
							//para cada artículo añadimos una opción
							echo '<option value=null></option>';
							foreach ($stmt as $row)
							{
								echo '<option value='.$row['Referencia'].'';							
								
								echo '>'.$row['Referencia'].'</option>';
							}
					}
					catch(PDOException $e ) {
						// tratamiento del error
						die("Error PDO: ".$e->GetMessage());
					}
						
					echo '</select></td>&nbsp&nbsp&nbsp&nbsp<td class=centrado><input name=etiquetas";
					var texto3 = " type=text size=3 maxlength=3 /></td></tr>";</script>';
				
			echo '				
				</table>
				<div id="articulo1" class="centrado"></div>				
				<table class="tabla-centrada">					
					<tr>
						<td class="centrado"><input name="otro" type="button" value="Otro artículo" class="boton" OnClick="javascript:otroArticulo();" /></td>
						<td class="centrado"><input name="generar" type="submit" value="Generar" class="boton" /></td> 
					</tr>	
				</table>
			</form>';
	}

	formularioEtiquetas();

	echo '<script type="text/javascript">otroArticulo();</script>';
	
	include_once('inc/footer.php');

?>