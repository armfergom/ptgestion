<?php
	
	include_once ('inc/misc.php'); 
	include_once ('inc/db_connect.php'); 
	
	if(isset($_REQUEST['cliente'])){

		$Id = $_REQUEST['cliente'];
		
		if(!isset($_REQUEST['si']) && !isset($_REQUEST['no'])){
			include_once('inc/header.php');
			$query = "SELECT Nombre,Apellidos FROM cliente WHERE IdCliente=$Id";
			$stmt = $dbh->query($query);	
			$row = $stmt->fetch();
			
			echo '<a href="menuClientes.php"><h4>Menú clientes</h4></a>
			<p class="parrafoCentrado">¿Está seguro de que desea eliminar al cliente '.$row['Nombre'].' '.$row['Apellidos'].'?
					<form method="post">
					<table class="tabla-centrada">
					<tr>
					<td><input name="si" type="submit" value="Sí" class="boton" /></td>
					<td><input name="no" type="submit" value="No" class="boton" /></td>
					</tr>
					</table>
					</form>';
		}
		
		else{
			if(isset($_REQUEST['si'])){
			include_once('inc/header.php');
				echo '<a href="menuClientes.php"><h4>Menú clientes</h4></a>';
				$query = "SELECT Nombre,Apellidos FROM cliente WHERE IdCliente=$Id";
				$stmt = $dbh->query($query);	
				$row = $stmt->fetch();
				$sql="DELETE FROM cliente WHERE IdCliente=$Id";
				try
					{
						if($dbh->exec($sql))
							echo '<p class="parrafoCentrado">Cliente '.$row['Nombre'].' '.$row['Apellidos'].' borrado.</p>';
						else{
							echo '<p class="parrafoCentrado">No se ha borrado el cliente. Puede que haya una referencia al mismo en alguna venta.</p>';
						}
					}
						catch(PDOException $e ) {
						// tratamiento del error
						die("Error PDO: ".$e->GetMessage());
					}
		
			}
			else{
				Header("Location:menuClientes.php");
			}
		}
		
	}
	include_once('inc/footer.php');

?>