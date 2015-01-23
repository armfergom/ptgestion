<?php
	
	include_once('inc/header.php');
	
	echo '<a href="menuClientes.php"><h4>Menú clientes</h4></a>
		<h4>Modificar clientes</h4>';
	
	if (isset($_REQUEST['cliente']))
	{	
		$Id = $_REQUEST['cliente'];
		
		if (!isset($_REQUEST['enviar']))
		{						
			$query = "SELECT NIF,Nombre,Apellidos,Titulo,Observaciones,Direccion,Localidad,Provincia,Pais,CP,Tlf1,Tlf2,Email FROM cliente WHERE IdCliente=$Id";
			$stmt = $dbh->query($query);	
			$row = $stmt->fetch();
			
			$nombre = $row['Nombre'];
			$NIF=$row['NIF'];
			$apellidos = $row['Apellidos'];
			$titulo = $row['Titulo'];
			$obs=$row['Observaciones'];
			$direccion = $row['Direccion'];
			$localidad = $row['Localidad'];
			$provincia = $row['Provincia'];
			$pais = $row['Pais'];
			$cp = $row['CP'];
			$telefono1 = $row['Tlf1'];
			$telefono2 = $row['Tlf2'];
			$email = $row['Email'];
			
			if ($cp == 0)
				$cp=null;
			if ($telefono1 == 0)
				$telefono1=null;
			if ($telefono2 == 0)
				$telefono2=null;
					
			formularioCliente($nombre, $apellidos, $NIF, $titulo,$obs, $direccion, $localidad, $provincia, $pais, $cp, $telefono1, $telefono2, $email);
		}		
		else
		{		
			$Id= $_REQUEST['cliente'];
			$NIF = $_REQUEST['nif'];
			$nombre = $_REQUEST['nombre'];
			$apellidos = $_REQUEST['apellidos'];
			$titulo = $_REQUEST['titulo'];
			$obs=$_REQUEST['obs'];
			$direccion = $_REQUEST['direccion'];
			$localidad = $_REQUEST['localidad'];
			$provincia = $_REQUEST['provincia'];
			$pais = $_REQUEST['pais'];
			$cp = (int)$_REQUEST['cp'];
			$telefono1 = (int)$_REQUEST['telefono1'];
			$telefono2 = (int)$_REQUEST['telefono2'];
			$email = $_REQUEST['email'];
			
			$errores = validaFormularioCliente($nombre, $apellidos, $nif, $titulo,$obs, $direccion, $localidad, $provincia, $pais, $cp, $telefono1, $telefono2, $email);
			
			if (!$errores)
			{
				$sql = "UPDATE cliente SET Nombre='$nombre', Apellidos='$apellidos', NIF='$NIF', Titulo='$titulo', Observaciones='$obs', Direccion='$direccion', Localidad='$localidad', Provincia='$provincia', Pais='$pais', CP=$cp, Tlf1=$telefono1, Tlf2=$telefono2, Email='$email' WHERE IdCliente=$Id";
				if($dbh->exec($sql))
					echo '<p class="parrafoCentrado">Cliente modificado. Ver <a href="datosCliente.php?cliente='.$Id.'">cliente.</a></p>';
				else
					echo '<p class="parrafoCentrado">No se ha modificado el cliente.</p>';
			}
			else
			{
				echo '<div class="erroresFormularios"><ul>'.$errores.'</ul></div>';
				if ($cp == 0)
					$cp=null;
				if ($telefono1 == 0)
					$telefono1=null;
				if ($telefono2 == 0)
					$telefono2=null;
				
				formularioCliente($nombre, $apellidos, $NIF, $titulo,$obs, $direccion, $localidad, $provincia, $pais, $cp, $telefono1, $telefono2, $email);
			}
		}
	}
	
	include_once('inc/footer.php');

?>