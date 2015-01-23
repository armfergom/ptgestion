<?php
	
	include_once('inc/header.php');
	//si llega información del formulario, la tratamos
	if (isset($_REQUEST['enviar']))
	{
		//obtenemos los datos del formulario
		$nombre = $_REQUEST['nombre'];
		$direccion = $_REQUEST['direccion'];
		$localidad = $_REQUEST['localidad'];
		$provincia = $_REQUEST['provincia'];
		$pais = $_REQUEST['pais'];
		$cp = (int)$_REQUEST['cp'];
		$telefono1 = (int)$_REQUEST['telefono1'];
		$telefono2 = (int)$_REQUEST['telefono2'];
		$fax = (int)$_REQUEST['fax'];
		$email = $_REQUEST['email'];
		
		//los validamos
		$errores = validaFormularioProveedor($nombre, $direccion, $localidad, $provincia, $pais, $cp, $telefono1, $telefono2, $fax, $email);
		
		//si no hay errores
		if (!$errores)
		{		
			//inserta todo salvo la imagen
			$sql = "INSERT INTO proveedor (Nombre, Direccion, Localidad, Provincia, Pais, CP, Tlf1, Tlf2, Fax, Email) VALUES ('$nombre', '$direccion', '$localidad', '$provincia', '$pais', $cp, $telefono1, $telefono2, $fax, '$email')";			

			try
			{
				//si el resto de los campos se insertaron correctamente
				if ($dbh->exec($sql))
					echo '<a href="menuProveedores.php"><h4>Menú proveedores</h4></a><p class="parrafoCentrado">Proveedor dado de alta. Dar de alta <a href="altaProveedor.php">otro</a> proveedor.</p>';
				else
					echo '<a href="menuProveedores.php"><h4>Menú proveedores</h4></a><p class="parrafoCentrado">El proveedor no se dio de alta. Por favor contacte con el administrador.</p>';
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
			if ($cp == 0)
				$cp=null;
			if ($telefono1 == 0)
				$telefono1=null;
			if ($fax == 0)
				$fax=null;
			if ($telefono2 == 0)
				$telefono2=null;
				
			formularioProveedor($nombre, $direccion, $localidad, $provincia, $pais, $cp, $telefono1, $telefono2, $fax, $email);
		}
	} 
	//si no, lo mostramos
	else 
	{
		formularioProveedor('','','','','','','','','','');
	}

	include_once('inc/footer.php');

?>