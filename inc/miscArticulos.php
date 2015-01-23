<?php 

include_once ('misc.php'); 

//Funcion de javascript, que eactualiza el coste y la referencia del proveedor en caso de que el proveedor elegido sea Becara
//Si el proveedor es Becara el coste es la mitad del precio y la referencia del proveedor la misma que la referencia del art’culo
echo '<script type="text/javascript">
			var numero = 1;
			function actualizaDatosAlta(){
				
				var proveedor = document.getElementById("proveedor").value;
				
				if (proveedor == 1){
					var coste = (document.getElementById("precio").value / 2);
					var refProv = document.getElementById("ref").value;
				
					document.getElementById("coste").disabled = true;
					document.getElementById("coste").value = coste;
					document.getElementById("refProveedor").disabled = true;
					document.getElementById("refProveedor").value = refProv;
				}
				else{
					document.getElementById("coste").disabled = false;
					document.getElementById("refProveedor").disabled = false;
				}
				
			}
			
			function activaBotones(){
				document.getElementById("coste").disabled = false;
				document.getElementById("refProveedor").disabled = false;
			}
		  </script>';


 function subirImagen($referencia)
	{
		global $dbh;
			
		//vemos si hay imagen subida (si no, no se hace nada)
		if (is_uploaded_file($_FILES['imagen']['tmp_name'])) 
		{
			//tamaño máximo de imagen (1 MB)
			$maxsize = 409600;
			//comprobamos que la imagen sea menor que el tamaño máximo
			if($_FILES['imagen']['size'] < $maxsize)
			{
				//si lo es, obtenemos la imgData, que es lo que hay que meter después en la base de datos
				$imgData = addslashes(file_get_contents($_FILES['imagen']['tmp_name']));
		 
				//nuestra consulta
				$sql = "UPDATE articulo SET Imagen = '{$imgData}' WHERE Referencia = $referencia";
		 
				//y la ejecutamos
				if(!$dbh->exec($sql)) 
				{
					echo 'Hubo un error al subir la imagen. Por favor, contacte con el administrador.';
				}
			}
			else 
			{
				 //si la imagen es demasiado grande, avisamos
				 echo
				  '<div>La imagen es demasiado grande.</div>
				  <div>El tamaño máximo de imagen es: '.$maxsize.' bytes.</div>
				  <div>La imagen '.$_FILES['imagen']['name'].' ocupa '.$_FILES['imagen']['size'].' bytes</div>
				  <hr />';
			}
		}
	}
	
	//valida el formulario
	function validaFormularioArticulo($referencia, $nombre, $precio, $coste, $referenciaProveedor, $observaciones, $idProveedor)
	{
		$errores = '';
		//validamos la referencia
		if($referencia == null)
			$errores .= '<li>La referencia es un campo obligatorio.</li>';
		else{
			if(!preg_match('/^[0-9]+$/',$referencia)){
				$errores .= '<li>Referencia con carácteres erróneos.</li>';
			}
			else{
				if($idProveedor == 1 && !preg_match('/^[0-9]{7}$/',$referencia)){
					$errores .= '<li>Referencia incorrecta. Las referencias de Becara han de tener 7 dígitos.</li>';
				}
				if($idProveedor != 1 && !preg_match('/^[0-9]{8}$/',$referencia)){
						$errores .= '<li>Referencia incorrecta. Las referencias que no sean de Becara han de tener 8 dígitos.</li>';
				}
			}
		}
		
		//validamos el nombre
		if($nombre == null)
			$errores .='<li>El nombre del artículo es un campo obligatorio.</li>';
		
		if (!esIntangible($referencia))
		{
			//validamos el precio y el coste
			$errores .= validaPrecio($precio,'precio');
			$errores .= validaPrecio($coste,'coste');
		}
				
		return $errores;
	}
	
	function validaPrecio($p, $c)
	{
		$e='';
		
		if($p == null)
			$e .= '<li>El '.$c.' es un campo obligatorio.</li>';
		else{
			if(!($p == strval(floatval($p))))
				$e .= '<li>El '.$c.' ha de ser un número decimal (usando el punto y no la coma como separador).</li>';
		}
		
		return $e;
	}

	//muestra el formulario de altas de artículos
	function formularioArticulo($referencia, $nombre, $precio, $coste, $referenciaProveedor, $observaciones, $idProveedor)
	{
		global $dbh;
		
		echo '
			<a href="menuArticulos.php"><h4>Menú artículos</h4></a>
			<h4>Alta de artículos</h4>
			<form method="post" enctype="multipart/form-data"> 
				<table class="tabla-centrada">
					<tr>
						<td class="alineado-izquierda"><label for="referencia"><b>Referencia:</b></label></td>
						<td class="alineado-derecha"><input name="referencia" id="ref" type="text" maxlength="8" value="'.$referencia.'" onchange="actualizaDatosAlta()"/></td>
					</tr>
					<tr>
						<td class="alineado-izquierda"><label for="nombre"><b>Nombre:</b></label></td>
						<td class="alineado-derecha"><input name="nombre" type="text" value="'.$nombre.'" onchange="actualizaDatosAlta()"/></td>
					</tr>
					<tr>
						<td class="alineado-izquierda"><label for="precio"><b>Precio(Sin IVA):</b></label></td>
						<td class="alineado-derecha"><input name="precio" id="precio" type="text" value="'.$precio.'" onchange="actualizaDatosAlta()"/></td>
					</tr>
					<tr>
						<td class="alineado-izquierda"><label for="coste"><b>Coste:</b></label></td>
						<td class="alineado-derecha"><input name="coste" id="coste" type="text"value="'.$coste.'"/></td>
					</tr>
					<tr>
						<td class="alineado-izquierda"><label for="referenciaProveedor"><b>Referencia proveedor:</b></label></td>
						<td class="alineado-derecha"><input name="referenciaProveedor" id="refProveedor" type="text" value="'.$referenciaProveedor.'"/></td>
					</tr>
					<tr>
						<td class="alineado-izquierda"><label for="observaciones"><b>Observaciones:</b></label></td>
						<td class="alineado-derecha"><textarea name="observaciones" onchange="actualizaDatosAlta()">'.$observaciones.'</textarea></td>
					</tr>
					<tr>
						<td class="alineado-izquierda"><label for="idProveedor"><b>Proveedor:</b></label></td>
						<td class="alineado-derecha"><select name="idProveedor" id="proveedor" onchange="actualizaDatosAlta()">';
					
					//obtemos los proveedores de la tabla proveedor
					try {
							$query = "SELECT IdProveedor, Nombre FROM proveedor ORDER BY IdProveedor ASC";
							$stmt = $dbh->query($query);							
							
							//para cada proveedor añadimos una opción
							echo '<option value="null"></option>';
							foreach ($stmt as $row)
							{
								echo '<option value="'.$row['IdProveedor'].'"';
								
								//mantenemos la opción en caso de error
								if ($row['IdProveedor'] == $idProveedor)
									echo ' selected="selected"';
								
								echo '>'.$row['Nombre'].'</option>';
							}
					}
					catch(PDOException $e ) {
						// tratamiento del error
						echo 'fallo';
						die("Error PDO: ".$e->GetMessage());
					}
						
					echo '</select></td>
					</tr>
					<tr>
						<td class="alineado-izquierda"><label for="imagen"><b>Imagen:</b></label></td>
						<td class="alineado-derecha"><div class="cnt_upload"><input id="upload_value" value="" /><div class="upload"><input id="examinarMal" name="imagen" type="file" onchange="actualiza()"/></div></div></td>
					</tr>
					<tr>
						<td class="alineado-izquierda"></td>
						<td class="alineado-derecha"><input name="enviar" type="submit" value="Enviar" class="boton" onclick="activaBotones()"/></td> 
					</tr>	
				</table>
			</form>';
	}
	
	function getImage($articulo){
		global $dbh;
		
		$query = "SELECT Imagen FROM articulo WHERE Referencia = $articulo";
		$stmt = $dbh->query($query);
		$row = $stmt->fetch();
	
		return $row['Imagen'];
	}
?>