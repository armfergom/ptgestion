<?php
	
	include_once('inc/header.php');
	echo'
		<h4>Menú presupuestos</h4>
		<table class="tabla-centrada">
		<tr>
		<td><a href="altaPresupuesto.php"><button type="button" class="botonMP" id="botonAltaPresupuesto" onmouseover="aclaracionAltaPresupuesto()" onmouseout="aclaracionAltaPresupuesto2()">Altas</button></a></td>
		<td><a href="listadoPresupuestos.php"><button type="button" class="botonMP" id="botonListadoPresupuestos" onmouseover="aclaracionListadoPresupuestos()" onmouseout="aclaracionListadoPresupuestos2()">Listado</button></a></td>				
		</tr>
		</table>
		
		<div id="aclaracionV"><p><br/></p></div>';

	include_once('inc/footer.php');

?>