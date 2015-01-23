<?php
	
	include_once('inc/header.php');
	
	$listaBoda = $_REQUEST['listaBoda'];
	
	echo '<p class=parrafoCentrado><b>Por favor introduzca la cabecera para la lista de boda:</b></p>';
	
	echo '<form id="f1" target="_new" name="f1" method="post" action="imprimirListaBoda.php?listaBoda='.$listaBoda.'">
		  <p class="centrado">
		  <label for="cabecera"><b>Cabecera:</b></label><br />
		  <textarea name="cabecera" rows="5" cols="40"></textarea><br />
		  <input name="sigPaso" type="submit" value="Generar" class="boton" />
		  </p>
		  </form>';
	  
	include_once('inc/footer.php');

?>