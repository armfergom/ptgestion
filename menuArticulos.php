<?php
	
	include_once('inc/header.php');
	echo'
	<h4>Menú artículos</h4>
		<table class="tabla-centrada">
		<tr>
		<td><a href="altaArticulo.php"><button type="button" class="botonMP" id="botonAltaArt" onmouseover="aclaracionAltas()" onmouseout="aclaracionAltas2()">Altas</button></a></td>
		<td><a href="listadoArticulos.php"><button type="button" class="botonMP" id="botonListadoArt" onmouseover="aclaracionListadosArt()" onmouseout="aclaracionListadosArt2()">Listado</button></a></td>
		<td><a href="menuTipoBusqueda.php"><button type="button" class="botonMP" id="botonBusquedaArt" onmouseover="aclaracionBusquedaArt()" onmouseout="aclaracionBusquedaArt2()">Búsqueda</button></td>
		<td><a href="etiquetas.php"><button type="button" class="botonMP" id="botonEtiquetas" onmouseover="aclaracionEtiquetas()" onmouseout="aclaracionEtiquetas2()">Etiquetas</button></td>
		</tr>
		</table>
		
		<div id="aclaracionA"><p><br/></p></div>';

	include_once('inc/footer.php');

?>