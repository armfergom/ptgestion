<?php
	
	include_once('inc/header.php');
	echo'
		<h4>Menú listas de boda</h4>
		<table class="tabla-centrada">
		<tr>
		<td><a href="altaListaBoda.php"><button type="button" class="botonMP" id="botonAltaListaBoda" onmouseover="aclaracionAltaListaBoda()" onmouseout="aclaracionAltaListaBoda2()">Altas</button></a></td>
		<td><a href="listadoListasBoda.php"><button type="button" class="botonMP" id="botonListadoListasBoda" onmouseover="aclaracionListadoListasBoda()" onmouseout="aclaracionListadoListasBoda2()">Listado</button></a></td>				
		</tr>
		</table>
		
		<div id="aclaracionV"><p><br/></p></div>';

	include_once('inc/footer.php');

?>