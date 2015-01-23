<?php
	
	include_once('inc/header.php');
	
	$venta = $_REQUEST['venta'];
	
	echo '
	<table class="tabla-centrada">
	<tr>
	<td><a href="imprimirTicketPrev.php?venta='.$venta.'&regalo=no" target="_new"><button type="button" class="botonMP" id="botonTicketBec" onmouseover="aclaracionTicketBec()" onmouseout="aclaracionTicketBec2()">Ticket normal</button></a></td>
	<td><a href="imprimirTicketPrev.php?venta='.$venta.'&regalo=si" target="_new"><button type="button" class="botonMP" id="botonTicketPT" onmouseover="aclaracionTicketPT()" onmouseout="aclaracionTicketPT2()">Ticket regalo</button></a></td>
	</tr>
	</table>';
	include_once('inc/footer.php');

?>