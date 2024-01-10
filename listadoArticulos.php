<?php
	
	include_once('inc/header.php');		
	//obtemos los artículos con todos sus atributos
	try 
	{
		echo'<table class="tabla-centrada">
		<tr>
		<td class="fila-centrada"><a href=inventario.php?becara=si target="_new"><button type="button" class="boton">Inventario Becara</button></a></td>
		<td class="fila-centrada"><a href=inventario.php?becara=no target="_new"><button type="button" class="boton">Inventario otros</button></a></td>
		</tr>
		</table>';
		
		$query = "SELECT Referencia, Nombre, Precio, Coste, IdProveedor, Unidades FROM articulo ORDER BY Referencia ASC";
		$stmt = $dbh->query($query);	
		 
		listaArt($stmt);
	}
	catch(PDOException $e ) {
		// tratamiento del error
		die("Error PDO: ".$e->GetMessage());
	}

	include_once('inc/footer.php');

?>