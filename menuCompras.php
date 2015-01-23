<?php
	
	include_once('inc/header.php');
	echo'
		<h4>Menú compras</h4>
		<table class="tabla-centrada">
		<tr>
		<td><a href="altaCompraPrev.php"><button type="button" class="botonMP" id="botonAltaCompra" onmouseover="aclaracionAltaCompra()" onmouseout="aclaracionAltaCompra2()">Altas</button></a></td>
		<td><a href="listadoCompras.php"><button type="button" class="botonMP" id="botonListadoCompras" onmouseover="aclaracionListadoCompras()" onmouseout="aclaracionListadoCompras2()">Listado</button></a></td>		
		</tr>
		</table>
		
		<div id="aclaracionC"><p><br/></p></div>';

	include_once('inc/footer.php');

?>