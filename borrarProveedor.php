<?php
	
	include_once ('inc/misc.php'); 
	include_once ('inc/db_connect.php'); 
	
	if(isset($_REQUEST['idProveedor'])){

		$Id = $_REQUEST['idProveedor'];
		
		if(!isset($_REQUEST['si']) && !isset($_REQUEST['no'])){
			include_once('inc/header.php');
			$query = "SELECT Nombre FROM proveedor WHERE IdProveedor=$Id";
			$stmt = $dbh->query($query);	
			$row = $stmt->fetch();
			
			echo '<a href="menuArticulos.php"><h4>Menú artículos</h4></a>
			<p class="parrafoCentrado">¿Está seguro de que desea eliminar el proveedor '.$row['Nombre'].'?
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
				echo '<a href="menuProveedores.php"><h4>Menú proveedores</h4></a>';
				$query = "SELECT Nombre FROM proveedor WHERE IdProveedor=$Id";
				$stmt = $dbh->query($query);	
				$row = $stmt->fetch();
				$sql="DELETE FROM proveedor WHERE IdProveedor=$Id";
				try
					{
						if($dbh->exec($sql))
							echo '<p class="parrafoCentrado">Proveedor '.$row['Nombre'].' borrado.</p>';
						else{
							echo '<p class="parrafoCentrado">No se ha borrado el proveedor. Puede que haya una referencia al mismo en alguna compra.</p>';
						}
					}
						catch(PDOException $e ) {
						// tratamiento del error
						die("Error PDO: ".$e->GetMessage());
					}
		
			}
			else{
				Header("Location:menuProveedores.php");
			}
		}
		
	}
	include_once('inc/footer.php');

?>