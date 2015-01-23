<?php
	
	include_once('inc/header.php');
	
	$IdPresupuesto = $_REQUEST['presupuesto'];
	
	echo '<p class=parrafoCentrado><b>Por favor complete la información necesaria para esta venta:</b></p>';
	
	echo '<form id="f1" name="f1" method="post" action="venderPresupuesto.php?presupuesto='.$IdPresupuesto.'"><table class="tabla-centrada">
		<tr>
		<td class="alineado-izquierda"><label for="forma"><b>Forma de pago</b></label></td>
		<td><select name="forma">
		<option value="null"></option>
		<option value="Visa">Visa</option>
		<option value="Efectivo">Efectivo</option>
		<option value="Transferencia">Transferencia</option>
		<option value="Talón">Talón</option>
		<option value="De su lista de boda">De su lista de boda</option></select>
		</select></td></tr><tr>
		<td class="alineado-izquierda">
		<label for="antiguedad"><b>Antigüedad</b></label></td><td>
		<select name="antiguedad">		
		<option value="No">No</option>
		<option value="Si">Sí</option>
		</select></td>
		</tr>
		<tr><td></td><td class="alineado-derecha"><input name="sigPaso" type="submit" value="Vender" class="boton" /></td></tr></table>';
		echo '</form>';
	include_once('inc/footer.php');

?>