<?php
	
	include_once('inc/header.php');
    //sube una imagen para el artículo identificado por $referencia
   
	//si llega información del formulario, la tratamos
	if (isset($_REQUEST['enviar']))
	{
		//obtenemos los datos del formulario
		$referencia = $_REQUEST['referencia'];
		$nombre = $_REQUEST['nombre'];
		$precio = $_REQUEST['precio'];
		$coste = $_REQUEST['coste'];
		$referenciaProveedor = $_REQUEST['referenciaProveedor'];
		$observaciones = $_REQUEST['observaciones'];
		$idProveedor = $_REQUEST['idProveedor'];
		$imagen = $_FILES['imagen'];
		
		//los validamos (excepto la imagen)
		$errores = validaFormularioArticulo($referencia, $nombre, $precio, $coste, $referenciaProveedor, $observaciones, $idProveedor);
		
		//si no hay errores
		if (!$errores)
		{		
			//el precio y coste de los intangibles lo ponemos a cero
			if (esIntangible($referencia))
			{
				$precio = 0;
				$coste = 0;
			}
			
			//inserta todo salvo la imagen
			$sql = "INSERT INTO articulo (Referencia, Nombre, Precio, Coste, ReferenciaProveedor, Observaciones, IdProveedor) VALUES ('$referencia', '$nombre', $precio, $coste, '$referenciaProveedor', '$observaciones', $idProveedor)";

			try
			{
				//si el resto de los campos se insertaron correctamente
				if ($dbh->exec($sql))
				{
					echo '<a href="menuArticulos.php"><h4>Menú artículos</h4></a><p class="parrafoCentrado">Artículo dado de alta. Dar de alta <a href="altaArticulo.php">otro</a> artículo.</p>';
					//subimos la imagen
					subirImagen($referencia);
				}
				else
					echo '<a href="menuArticulos.php"><h4>Menú artículos</h4></a><p class="parrafoCentrado">El artículo no se dio de alta. Por favor contacte con el administrador.</p>';
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
			formularioArticulo($referencia, $nombre, $precio, $coste, $referenciaProveedor, $observaciones, $idProveedor);			
		}
	} 
	//si no, lo mostramos
	else 
	{
		formularioArticulo('','','','','','','');
	}

	include_once('inc/footer.php');

?>