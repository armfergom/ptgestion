<?php
	
	include_once ('inc/misc.php'); 
	include_once ('inc/db_connect.php'); 
	
	if(isset($_REQUEST['articulo'])){

		$articulo = $_REQUEST['articulo'];
		
		if(!isset($_REQUEST['si']) && !isset($_REQUEST['no'])){
			include_once('inc/header.php');
			echo '<a href="menuArticulos.php"><h4>Men� art�culos</h4></a>
			<p class="parrafoCentrado">�Est� seguro de que desea eliminar el art�culo con referencia '.$articulo.'?
					<form method="post" enctype="multipart/form-data">
					<table class="tabla-centrada">
					<tr>
					<td><input name="si" type="submit" value="S�" class="boton" /></td>
					<td><input name="no" type="submit" value="No" class="boton" /></td>
					</tr>
					</table>
					</form>';
		}
		
		else{
			if(isset($_REQUEST['si'])){
			include_once('inc/header.php');
				echo '<a href="menuArticulos.php"><h4>Men� art�culos</h4></a>';
				$sql="DELETE FROM articulo WHERE Referencia='$articulo'";
				try
					{
						if($dbh->exec($sql))
							echo '<p class="parrafoCentrado">Art�culo con referencia '.$articulo.' borrado.</p>';
						else{
							echo '<p class="parrafoCentrado">No se ha borrado el art�culo. Puede que haya una referencia al mismo en alguna compra, venta, presupuesto o lista de boda.</p>';
						}
					}
						catch(PDOException $e ) {
						// tratamiento del error
						die("Error PDO: ".$e->GetMessage());
					}
		
			}
			else{
				Header("Location:menuArticulos.php");
			}
		}
		
	}
	include_once('inc/footer.php');

?>