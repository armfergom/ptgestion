<?php
	
	include_once('inc/header.php');
	
	if (isset($_REQUEST['idProveedor']))
	{		
		if (!isset($_REQUEST['enviar'])){
			$Id = $_REQUEST['idProveedor'];
			
			$query = "SELECT IdProveedor,Nombre,Direccion,Localidad,Provincia,Pais,CP,Tlf1,Tlf2,Fax,Email FROM proveedor WHERE IdProveedor=$Id";
			$stmt = $dbh->query($query);	
			$row = $stmt->fetch();
			
			$nombre = $row['Nombre'];
			$direccion = $row['Direccion'];
			$localidad = $row['Localidad'];
			$provincia = $row['Provincia'];
			$pais = $row['Pais'];
			$cp = $row['CP'];
			$telefono1 = $row['Tlf1'];
			$telefono2 = $row['Tlf2'];
			$fax = $row['Fax'];
			$email = $row['Email'];
			
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
		else{
			$Id = $_REQUEST['idProveedor'];
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
			
			$errores = validaFormularioProveedor($nombre, $direccion, $localidad, $provincia, $pais, $cp, $telefono1, $telefono2, $fax, $email);
			
			if (!$errores){
				$sql = "UPDATE proveedor SET Nombre='$nombre', Direccion='$direccion', Localidad='$localidad', Provincia='$provincia', Pais='$pais', CP=$cp, Tlf1=$telefono1, Tlf2=$telefono2, Fax=$fax, Email='$email' WHERE IdProveedor=$Id";
				if($dbh->exec($sql)){
					echo '<a href="menuProveedores.php"><h4>Menú proveedores</h4></a><p class="parrafoCentrado">Proveedor modificado.</p>';
				}
				else
					echo '<a href="menuProveedores.php"><h4>Menú proveedores</h4></a><p class="parrafoCentrado">No se ha modificado el proveedor.</p>';

			}
			else{
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
	}
	
	include_once('inc/footer.php');

?>