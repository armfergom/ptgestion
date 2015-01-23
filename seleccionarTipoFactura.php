<?php
	
	include_once('inc/header.php');
	echo '<a href="menuVentas.php"><h4>Menú ventas</h4></a>';
	echo '<p class=parrafoCentrado><b>Por favor seleccione el tipo de factura a imprimir:</b></p>';
	
	echo '<table class="tabla-centrada">
		<tr>';
		
	if (!isset($_REQUEST['presupuesto'])){
		$venta=$_REQUEST['venta'];
		echo '<td><a href="imprimirFacturaPrev.php?venta='.$venta.'" target="_new"><input name="imprimirFactura1" type="button" value="Factura normal" class="boton" /></a></td>';
		echo '<td><a href="imprimirFacturaResumenPrev.php?venta='.$venta.'"><input name="imprimirFacturaResumen" type="button" value="Factura resumen" class="boton" /></a></td>';
	}
	else{
		$presupuesto=$_REQUEST['presupuesto'];
		echo '<td><a href="imprimirFacturaPrev.php?presupuesto='.$presupuesto.'&facturaPresupuesto=si" target="_new"><input name="imprimirFactura2" type="button" value="Factura normal" class="boton" /></a></td>';
		echo '<td><a href="imprimirFacturaResumenPrev.php?presupuesto='.$presupuesto.'"><input name="imprimirFacturaResumen" type="button" value="Factura resumen" class="boton" /></a></td>';
	}

	echo '</tr></table>';
		
	include_once('inc/footer.php');

?>