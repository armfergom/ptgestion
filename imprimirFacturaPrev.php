<?php
	
	include_once('inc/header.php');
	
	$venta = $_REQUEST['venta'];
	
	if (isset($_REQUEST['presupuesto'])){
		$presupuesto = $_REQUEST['presupuesto'];
		echo '
		<table class="tabla-centrada">
		<tr>
		<td><a href="imprimirPresupuesto.php?presupuesto='.$presupuesto.'&facturaPresupuesto=si&electronica=no" target="_new"><button type="button" class="botonMP" id="botonTicketBec" onmouseover="aclaracionTicketBec()" onmouseout="aclaracionTicketBec2()">Factura en papel</button></a></td>
		<td><a href="imprimirPresupuesto.php?presupuesto='.$presupuesto.'&facturaPresupuesto=si&electronica=si" target="_new"><button type="button" class="botonMP" id="botonTicketPT" onmouseover="aclaracionTicketPT()" onmouseout="aclaracionTicketPT2()">Factura electrónica</button></a></td>
		</tr>
		</table>';
	}
	else
	{
		$venta = $_REQUEST['venta'];
		echo '
		<table class="tabla-centrada">
		<tr>
		<td><a href="imprimirFactura.php?venta='.$venta.'&electronica=no" target="_new"><button type="button" class="botonMP" id="botonTicketBec" onmouseover="aclaracionTicketBec()" onmouseout="aclaracionTicketBec2()">Factura en papel</button></a></td>
		<td><a href="imprimirFactura.php?venta='.$venta.'&electronica=si" target="_new"><button type="button" class="botonMP" id="botonTicketPT" onmouseover="aclaracionTicketPT()" onmouseout="aclaracionTicketPT2()">Factura electrónica</button></a></td>
		</tr>
		</table>';
	}
	include_once('inc/footer.php');

?>