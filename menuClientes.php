<?php
	
	include_once('inc/header.php');
	echo'
	<h4>Menú clientes</h4>
		<table class="tabla-centrada">
		<tr>
		<td><a href="altaCliente.php"><button type="button" class="botonMP" id="botonAltaCli" onmouseover="aclaracionAltasCli()" onmouseout="aclaracionAltasCli2()">Altas</button></a></td>
		<td><a href="listadoClientes.php"><button type="button" class="botonMP" id="botonListadoCli" onmouseover="aclaracionListadosCli()" onmouseout="aclaracionListadosCli2()">Listado</button></a></td>
		<td><a href="busquedaCliente.php"><button type="button" class="botonMP" id="botonBusquedaCli" onmouseover="aclaracionBusquedaCli()" onmouseout="aclaracionBusquedaCli2()">Búsqueda</button></a></td>
		</tr>
		</table>
		
		<div id="aclaracionC"><p><br/></p></div>';

	include_once('inc/footer.php');

?>