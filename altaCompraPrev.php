<?php
	
	include_once('inc/header.php');
	
	$query = "SELECT IdProveedor,Nombre FROM proveedor ORDER BY Nombre ASC";
	$stmt = $dbh->query($query);
	
	echo '<p class=parrafoCentrado><b>Por favor seleccione el proveedor:</b></p>';
	
	echo '<form id="f1" name="f1" method="post" action="altaCompra.php"><table class="tabla-centrada"><tr><td class="alineado-izquierda"><label for="proveedor"><b>Proveedor</b></label></td><td><select name="proveedor">';
		//para cada artículo añadimos una opción
		echo '<option value="null"></option>';
		foreach ($stmt as $row)
		{
			echo '<option value='.$row['IdProveedor'].'';											
			echo '>'.$row['Nombre'].'</option>';
		};
	

	echo '</select></td></tr><tr><td></td><td class="alineado-derecha"><input name="sigPaso" type="submit" value="Continuar" class="boton" /></td></tr></table>';
		echo '</form>';
	include_once('inc/footer.php');

?>