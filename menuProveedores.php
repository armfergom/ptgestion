<?php
	
	include_once('inc/header.php');
	echo'
	<h4>Menú proveedores</h4>
		<table class="tabla-centrada">
		<tr>
		<td><a href="altaProveedor.php"><button type="button" class="botonMP" id="botonAltaProv" onmouseover="aclaracionAltasProv()" onmouseout="aclaracionAltasProv2()">Altas</button></a></td>
		<td><a href="listadoProveedores.php"><button type="button" class="botonMP" id="botonListadoProv" onmouseover="aclaracionListadosProv()" onmouseout="aclaracionListadosProv2()">Listado</button></a></td>
		<td><a href="busquedaProveedor.php"><button type="button" class="botonMP" id="botonBusquedaProv" onmouseover="aclaracionBusquedaProv()" onmouseout="aclaracionBusquedaProv2()">Búsqueda</button></a></td>
		</tr>
		</table>
		
		<div id="aclaracionP"><p><br/></p></div>';

	include_once('inc/footer.php');

?>