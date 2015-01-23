<?php
	
	include_once('inc/header.php');
	
	//si llega información del formulario, la tratamos
	if (isset($_REQUEST['articulo']))
	{		
		if (!isset($_REQUEST['enviar'])){
			$referencia = $_REQUEST['articulo'];
			
			$query = "SELECT Nombre, Precio, Coste,ReferenciaProveedor,Observaciones,Imagen,IdProveedor FROM articulo WHERE Referencia=$referencia";
			$stmt = $dbh->query($query);	
			$row = $stmt->fetch();
			
			$nombre = $row['Nombre'];
			$precio = $row['Precio'];
			$coste = $row['Coste'];
			$referenciaProveedor = $row['ReferenciaProveedor'];
			$observaciones = $row['Observaciones'];
			$idProveedor = $row['IdProveedor'];
			formularioArticulo($referencia, $nombre, $precio, $coste, $referenciaProveedor, $observaciones, $idProveedor);
		}		
		else{
			$temp = $_REQUEST['articulo'];
			$query2="SELECT Imagen FROM articulo WHERE Referencia = '$temp'";
			$stmt = $dbh->query($query2);	
			$row = $stmt->fetch();
			$imagenTemporal = $row['Imagen'];
			
			$referenciaTemporal = $_REQUEST['articulo'];
			
			$referencia = $_REQUEST['referencia'];
			$nombre = $_REQUEST['nombre'];
			$precio = $_REQUEST['precio'];
			$coste = $_REQUEST['coste'];
			$referenciaProveedor = $_REQUEST['referenciaProveedor'];
			$observaciones = $_REQUEST['observaciones'];
			$idProveedor = $_REQUEST['idProveedor'];
			$imagen = $_REQUEST['imagen'];
			
			$errores=validaFormularioArticulo($referencia, $nombre, $precio, $coste, $referenciaProveedor, $observaciones, $idProveedor);
			
			if (!$errores)
			{	
				//el precio y coste de los intangibles lo ponemos a cero
				if (esIntangible($referencia))
				{
					$precio = 0;
					$coste = 0;
				}
				
				$sql = "UPDATE articulo SET Referencia='$referencia',Nombre='$nombre',Precio=$precio,Coste=$coste,ReferenciaProveedor='$referenciaProveedor',Observaciones='$observaciones',IdProveedor=$idProveedor WHERE Referencia='$referenciaTemporal'";
				if($dbh->exec($sql)){
					echo '<a href="menuArticulos.php"><h4>Menú artículos</h4></a><p class="parrafoCentrado">Articulo modificado.</p>';
					subirImagen($referencia);
				}
				else
					if(is_uploaded_file($_FILES['imagen']['tmp_name'])){
						subirImagen($referencia);	
						echo '<a href="menuArticulos.php"><h4>Menú artículos</h4></a><p class="parrafoCentrado">Articulo modificado.</p>';
					}
					else
						echo '<a href="menuArticulos.php"><h4>Menú artículos</h4></a><p class="parrafoCentrado">No se ha modificado el artículo.</p>';

			}
			else{
				echo '<div class="erroresFormularios"><ul>'.$errores.'</ul></div>';
				formularioArticulo($referencia, $nombre, $precio, $coste, $referenciaProveedor, $observaciones, $idProveedor);
			}
		}
	}
	
	include_once('inc/footer.php');

?>