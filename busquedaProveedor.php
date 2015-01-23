<?php

	include_once('inc/header.php');
	
	function formularioBusquedaProveedor()
	{		
		echo '
		<a href="menuProveedores.php"><h4>Menú proveedores</h4></a>
		<h4>Búsqueda de proveedores</h4>
		<form method="post"> 
			<table class="tabla-centrada">
			<tr>
			<td><label for="nombre" ><b>Nombre:</b></label></td>
				<td><input name="nombre" type="text"/></td>
			</tr>
			<tr>
				<td></td>
				<td><input name="buscarProveedor" type="submit" value="Buscar" class="boton" /></td>
			</tr>
			</table>
		</form>';		
	}
	
	if (isset($_REQUEST['buscarProveedor'])){
			$nombre=$_REQUEST['nombre'];
			$query="SELECT IdProveedor, Nombre, Direccion, Localidad, Tlf1, Email FROM proveedor WHERE Nombre LIKE '%$nombre%'";
			
			try {
				$stmt = $dbh->query($query);
				$num = $stmt->rowCount();
				
				if($num >0){
					listaProv($stmt);
				}
				else
					echo '<a href="menuProveedores.php"><h4>Menú proveedores</h4></a><p class="parrafoCentrado">No se ha encontrado ningún proveedor con ese nombre.</p>';
			}
			catch(PDOException $e ) {
				// tratamiento del error
				die("error: ".$e->GetMessage());
			}	
	}
	else{
		formularioBusquedaProveedor();
	}

	
	include_once('inc/footer.php');

?>