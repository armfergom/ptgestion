<?php

	include_once('inc/header.php');
	
	function formularioBusquedaCliente()
	{		
		echo '
		<a href="menuClientes.php"><h4>Menú clientes</h4></a>
		<h4>Búsqueda de clientes</h4>
		<form method="post"> 
			<table class="tabla-centrada">
			<tr>
			<td><label for="apellidos" ><b>Apellidos:</b></label></td>
				<td><input name="apellidos" type="text"/></td>
			</tr>
			<tr>
				<td></td>
				<td><input name="buscarCliente" type="submit" value="Buscar" class="boton" /></td>
			</tr>
			</table>
		</form>';		
	}
	
	if (isset($_REQUEST['buscarCliente'])){
			$apellidos=$_REQUEST['apellidos'];
			$query="SELECT IdCliente,NIF, Titulo, Nombre, Apellidos, Tlf1, Direccion,CP FROM cliente WHERE Apellidos LIKE '%$apellidos%'";
			
			try {
				$stmt = $dbh->query($query);
				$num = $stmt->rowCount();
				
				if($num >0){
					listaCli($stmt);
				}
				else
					echo '<a href="menuClientes.php"><h4>Menú clientes</h4></a><p class="parrafoCentrado">No se ha encontrado ningún cliente con esos apellidos.</p>';
			}
			catch(PDOException $e ) {
				// tratamiento del error
				die("error: ".$e->GetMessage());
			}	
	}
	else{
		formularioBusquedaCliente();
	}

	
	include_once('inc/footer.php');

?>