<?php
	
	include_once('inc/header.php');
	echo'
		<a href="menuArticulos.php"><h4>Menú artículos</h4></a>
		<h4>Menú tipo de búsqueda</h4>
		<table class="tabla-centrada">
		<tr>
		<td><a href="busquedaArticulo.php?tipo=1"><button type="button" class="botonMP" id="botonBusquedaRef" onmouseover="aclaracionBusquedaRef()" onmouseout="aclaracionBusquedaRef2()">Por referencia</button></a></td>
		<td><a href="busquedaArticulo.php?tipo=2"><button type="button" class="botonMP" id="botonBusquedaNom" onmouseover="aclaracionBusquedaNom()" onmouseout="aclaracionBusquedaNom2()">Por nombre</button></a></td>		
		</tr>
		</table>
		
		<div id="aclaracionT"><p><br/></p></div>';

	include_once('inc/footer.php');

?>