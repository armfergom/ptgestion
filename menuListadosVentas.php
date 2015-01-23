<?php
	
	include_once('inc/header.php');
	echo'
		<a href="menuVentas.php"><h4>Menú ventas</h4></a>
		<table class="tabla-centrada">
		<tr>
		<td><a href="listadoVentas.php?tipo=ticket"><button type="button" class="botonMP" id="botonListadoVentasTicket" onmouseover="aclaracionListadoVentasTicket()" onmouseout="aclaracionListadoVentasTicket2()">Listado ticket</button></a></td>				
		<td><a href="listadoVentas.php?tipo=factura"><button type="button" class="botonMP" id="botonListadoVentasFactura" onmouseover="aclaracionListadoVentasFactura()" onmouseout="aclaracionListadoVentasFactura2()">Listado factura</button></a></td>
		<td><a href="listadoVentas.php?"><button type="button" class="botonMP" id="botonListadoVentas" onmouseover="aclaracionListadoVentas()" onmouseout="aclaracionListadoVentas2()">Ambas</button></a></td>		
		</tr>
		</table>
		
		<div id="aclaracionV"><p><br/></p></div>';

	include_once('inc/footer.php');

?>