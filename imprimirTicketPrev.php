<?php
	
	include_once('inc/header.php');
	
	$venta = $_REQUEST['venta'];
	$regalo = $_REQUEST['regalo'];
	
	echo '
	<table class="tabla-centrada">
	<tr>
	<td><a href="imprimirTicket.php?venta='.$venta.'&regalo='.$regalo.'&tienda=Becara" target="_new"><button type="button" class="botonMP" id="botonTicketBec" onmouseover="aclaracionTicketBec()" onmouseout="aclaracionTicketBec2()">Becara</button></a></td>
	<td><a href="imprimirTicket.php?venta='.$venta.'&regalo='.$regalo.'&tienda=PT" target="_new"><button type="button" class="botonMP" id="botonTicketPT" onmouseover="aclaracionTicketPT()" onmouseout="aclaracionTicketPT2()">PT</button></a></td>
	</tr>
	</table>';
	include_once('inc/footer.php');

?>