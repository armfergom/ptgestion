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
		while(isset($_REQUEST["referencia$i"]) && isset($_REQUEST["unidades$i"]))
		{				
			$referencia = $_REQUEST["referencia$i"];
			$unidades = $_REQUEST["unidades$i"];
					
			//////////////////
			if ($referencia == 'null' xor $unidades == null)
				$errores .= '<li>La referencia y las unidades son campos obligatorios.</li>';
			else
			{
				if ($unidades != null)
				{
					//ver que las unidades sea un número positivo
					if (!preg_match('/^[1-9][0-9]*$/',$unidades))
						$errores .= "<li>Unidades debe ser un número positivo.</li>";
					else
						$articulos += 1;
				}
			}
			
			//////////////////
						
			$i += 1;
		}
		
		if ($articulos == 0)
			$errores .= "<li>Una compra debe tener al menos un artículo.</li>";

		return $errores;
	}

	//muestra el formulario de altas de compras
	function formularioAltaCompra($proveedor)
	{
		global $dbh;

		echo '<a href="menuCompras.php"><h4>Menú compras</h4></a>
			<h4>Alta de compras</h4>';
			$compra_aux=$_REQUEST['compra'];
			if (isset($_REQUEST['continuar']))
				echo '<form id="f1" name="f1" method="post" action="altaCompra.php?proveedor='.$proveedor.'&compra='.$compra_aux.'&continuar=si">'; 
			else
				echo '<form id="f1" name="f1" method="post" action="altaCompra.php?proveedor='.$proveedor.'">'; 
			echo'<table class="tabla-centrada">
					<tr>
						<td class="centrado"><label for="referencia"><b>Referencia</b></label></td>
						<td class="centrado"><label for="unidades"><b>Unidades</b></label></td>
					</tr>';
				
			echo '<script type="text/javascript">
					var texto1 = "<tr><td class=centrado><select name=referencia";
					var texto2= ">';
					
					//obtemos los artículos de la tabla artículo
					try {
						if ($proveedor!="null")
						{
							$query = "SELECT Referencia FROM articulo WHERE IdProveedor=$proveedor ORDER BY Referencia ASC";
							$stmt = $dbh->query($query);	
						}
						else
						{
							$query = "SELECT Referencia FROM articulo WHERE IdProveedor is NULL ORDER BY Referencia ASC";
							$stmt = $dbh->query($query);	
						}					
							
							//para cada artículo añadimos una opción
							echo '<option value=null></option>';
							foreach ($stmt as $row)
							{
								if (!esIntangible($row['Referencia']))
								{
									echo '<option value='.$row['Referencia'].'';							
									
									echo '>'.$row['Referencia'].'</option>';
								}
							}
					}
					catch(PDOException $e ) {
						// tratamiento del error
						die("Error PDO: ".$e->GetMessage());
					}
						
					echo '</select></td>&nbsp&nbsp&nbsp&nbsp<td class=centrado><input name=unidades";
					var texto3 = " type=text size=3 maxlength=3 /></td></tr>";</script>';
				
			echo '				
				</table>
				<div id="articulo1" class="centrado"></div>				
				<table class="tabla-centrada">					
					<tr>
						<td class="centrado"><input name="otro" type="button" value="Otro artículo" class="boton" OnClick="javascript:otroArticulo();" /></td>
						<td class="centrado"><input name="darDeAlta" type="submit" value="Dar de alta" class="boton" /></td> ';
			//echo '		<td class="centrado"><input name="generar" type="submit" value="Generar etiquetas" class="boton" onclick="f1.action=\'etiquetasImprimir.php\'; return true;" /></td> ';
			echo '	</tr>	
					</table>';
				
			if (!isset($_REQUEST['continuar']))
				echo '
					<div class="centrado">
					<br />
					<label for="observaciones"><b>Observaciones</b></label><br />
					<textarea name="observaciones" rows="5" cols="40"></textarea>
					</div>';
			
			echo '
			</form>';
	}

	//si llega información del formulario, la tratamos
	if (isset($_REQUEST['darDeAlta']))
	{		
		//obtenemos los datos del formulario
		$observaciones = $_REQUEST['observaciones'];
		$proveedor = $_REQUEST['proveedor'];
		//los validamos
		$errores = validaFormulario();
		
		//si no hay errores
		if (!$errores)
		{		
			//inserta compra (con la fecha actual CURrent DATE) si no estamos continuando una compra
			$sql = "INSERT INTO compra (Fecha, Observaciones) VALUES (curdate(), '$observaciones')";
			try
			{
				//si dio la compra de alta, damos de alta las líneas de compra
				if (isset($_REQUEST['continuar']) || $dbh->exec($sql))
				{
					if (isset($_REQUEST['compra']))
						$IdCompra = $_REQUEST['compra'];
					else
						$IdCompra = $dbh->lastInsertId();
					$i = 1;
					$errores = '';
					
					$enlace = 'distribuirEtiquetas.php?generar=1';
					
					while(isset($_REQUEST["referencia$i"]) && isset($_REQUEST["unidades$i"]))
					{				
						$referencia = $_REQUEST["referencia$i"];
						$unidades = $_REQUEST["unidades$i"];
						if ($referencia != 'null' && $unidades != null)
						{
							//obtenemos la información de ese artículo
							$query = "SELECT Unidades, Coste FROM articulo WHERE Referencia = '$referencia'";
							$stmt = $dbh -> query($query);
							$row = $stmt -> fetch();							
							$coste = $row['Coste'];
							
							//añadimos la línea de compra
							$sql = "INSERT INTO lineacompra (Referencia, IdCompra, Unidades, Coste) VALUES ('$referencia', $IdCompra, $unidades, $coste)";														
							
							if (!($dbh->exec($sql)))							
								$errores .= "<p>El artículo $referencia ya pertenece a la compra. Si desea modificar su número de unidades, elimínelo de la compra y añádalo de nuevo.</p>";							
							else
							{
								//construimos el enlace para etiquetas
								$enlace .= "&referencia$i=$referencia&etiquetas$i=$unidades";

								//incrementamos sus unidades
								$unidades += $row['Unidades'];
								
								$sql = "UPDATE articulo SET Unidades = $unidades WHERE Referencia = '$referencia'";
								$dbh -> exec($sql);
							}
						}
						$i += 1;
					}
					
					if (isset($_REQUEST['continuar']))
						echo '<a href="menuCompras.php"><h4>Menú compras</h4></a><p class="parrafoCentrado">La compra se modificó con éxito.<br /><a href="'.$enlace.'" target="_new">Generar etiquetas.</a><br /><a href="datosCompra.php?compra='.$IdCompra.'">Ver compra.</a></p>';
					else
						echo '<a href="menuCompras.php"><h4>Menú compras</h4></a><p class="parrafoCentrado">Compra dada de alta.<br /><a href="'.$enlace.'" target="_new">Generar etiquetas.</a><br /><a href="datosCompra.php?compra='.$IdCompra.'">Ver compra.</a></p>';
					
					echo $errores;
				}
				else
					echo '<a href="menuCompras.php"><h4>Menú compras</h4></a><p class="parrafoCentrado">Los artículos no se pudieron añadir a la compra. Por favor contacte con el administrador.</p>';
			}
			catch(PDOException $e ) 
			{
				die("Error PDO: ".$e->GetMessage());
			}
		}
		//si hay errores de validación
		else 
		{
			echo '<div class="erroresFormularios"><ul>'.$errores.'</ul></div>';
			formularioAltaCompra($proveedor);	
			echo '<script type="text/javascript">otroArticulo();</script>';
		}
	} 
	//si no, lo mostramos
	else 
	{
		$proveedor = $_REQUEST['proveedor'];
		formularioAltaCompra($proveedor);
		echo '<script type="text/javascript">otroArticulo();</script>';
	}
	
	include_once('inc/footer.php');

?>