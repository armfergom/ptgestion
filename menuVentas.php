<?php
	
	include_once('inc/header.php');
	echo'
		<h4>Menú ventas</h4>
		<table class="tabla-centrada">
		<tr>
		<td><a href="altaVenta.php?tipo=ticket"><button type="button" class="botonMP" id="botonTicket" onmouseover="aclaracionTicket()" onmouseout="aclaracionTicket2()">Ticket</button></a></td>
		<td><a href="altaVenta.php?tipo=factura"><button type="button" class="botonMP" id="botonFactura" onmouseover="aclaracionFactura()" onmouseout="aclaracionFactura2()">Factura</button></a></td>	
		<td><a href="menuListadosVentas.php"><button type="button" class="botonMP" id="botonListadoVentas" onmouseover="aclaracionListadoVentas()" onmouseout="aclaracionListadoVentas2()">Listado</button></a></td>				
		</tr>
		</table>
		
		<div id="aclaracionV"><p><br/></p></div>';

	include_once('inc/footer.php');

?>