<?php
	
	include_once('inc/header.php');

	echo '<a href="menuClientes.php"><h4>Menú clientes</h4></a>
		<h4>Alta de clientes</h4>';
	
	//si llega información del formulario, la tratamos
	if (isset($_REQUEST['enviar']))
	{
		//obtenemos los datos del formulario
		$nombre = $_REQUEST['nombre'];
		$apellidos = $_REQUEST['apellidos'];
		$nif = $_REQUEST['nif'];
		$titulo = $_REQUEST['titulo'];
		$obs= $_REQUEST['obs'];
		$direccion = $_REQUEST['direccion'];
		$localidad = $_REQUEST['localidad'];
		$provincia = $_REQUEST['provincia'];
		$pais = $_REQUEST['pais'];
		$cp = (int)$_REQUEST['cp'];
		$telefono1 = (int)$_REQUEST['telefono1'];
		$telefono2 = (int)$_REQUEST['telefono2'];
		$email = $_REQUEST['email'];
		
		//los validamos
		$errores = validaFormularioCliente($nombre, $apellidos, $nif, $titulo,$obs, $direccion, $localidad, $provincia, $pais, $cp, $telefono1, $telefono2, $email);
		
		//si no hay errores
		if (!$errores)
		{		
			//inserta
			$sql = "INSERT INTO cliente (NIF, Nombre, Apellidos, Titulo,Observaciones, Direccion, Localidad, Provincia, Pais, CP, Tlf1, Tlf2, FechaAlta, Email) VALUES ('$nif', '$nombre', '$apellidos', '$titulo','$obs', '$direccion', '$localidad', '$provincia', '$pais', $cp, $telefono1, $telefono2, curdate(), '$email')";										
			
			try
			{
				//si el resto de los campos se insertaron correctamente
				if ($dbh->exec($sql))
					echo '<p class="parrafoCentrado">Cliente dado de alta. Dar de alta <a href="altaCliente.php">otro</a> cliente.</p>';
				else
					echo '<p class="parrafoCentrado">El cliente no se dio de alta. Por favor contacte con el administrador.</p>';
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
			if ($telefono2 == 0)
				$telefono2=null;
			formularioCliente($nombre, $apellidos, $nif, $titulo,$obs, $direccion, $localidad, $provincia, $pais, $cp, $telefono1, $telefono2, $email);
		}
	} 
	//si no, lo mostramos
	else 
	{
		formularioCliente('', '', '', '','', '', '', '', '', '', '', '', '');
	}

	include_once('inc/footer.php');

?>