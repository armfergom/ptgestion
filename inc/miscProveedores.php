<?php
function validaFormularioProveedor($nombre, $direccion, $localidad, $provincia, $pais, $cp, $telefono1, $telefono2, $fax, $email)
	{
		$errores = '';
		
		//validamos el nombre
		if($nombre==null){
			$errores .= '<li>El nombre es un campo obligatorio</li>';
		}
		
		//validamos el CP
		if($cp != null)
			if(!preg_match('/^[0-9]{5}$/',$cp,$gr)){
				$errores .= '<li>El c�digo postal ha de ser un n�mero de 5 cifras</li>';
			}
		
		//validamos los tel�fonos y el fax
		$errores .= validaNumT($telefono1,'Tel�fono 1');
		$errores .= validaNumT($telefono2,'Tel�fono 2');
		$errores .= validaNumT($fax,'Fax');
		
		//validamos el email
		$errores .= valida_email($email);
		
		return $errores;
	}

	//muestra el formulario de altas de art�culos
	function formularioProveedor($nombre, $direccion, $localidad, $provincia, $pais, $cp, $telefono1, $telefono2, $fax, $email)
	{
		echo '
		<a href="menuProveedores.php"><h4>Men� proveedores</h4></a>
		<h4>Alta de proveedores</h4>
			<form method="post"> 
				<table class="tabla-centrada">
					<tr>
						<td class="alineado-izquierda"><label for="nombre"><b>Nombre:</b></label></td>
						<td class="alineado-derecha"><input name="nombre" type="text" value="'.$nombre.'"/></td>
					</tr>
					<tr>
						<td class="alineado-izquierda"><label for="direccion"><b>Direccion:</b></label></td>
						<td class="alineado-derecha"><input name="direccion" type="text" value="'.$direccion.'"/></td>
					</tr>
					<tr>
						<td class="alineado-izquierda"><label for="localidad"><b>Localidad:</b></label></td>
						<td class="alineado-derecha"><input name="localidad" type="text" value="'.$localidad.'"/></td>
					</tr>
					<tr>
						<td class="alineado-izquierda"><label for="provincia"><b>Provincia:</b></label></td>
						<td class="alineado-derecha"><input name="provincia" type="text" value="'.$provincia.'"/></td>
					</tr>
					<tr>
						<td class="alineado-izquierda"><label for="pais"><b>Pa�s:</b></label></td>
						<td class="alineado-derecha"><input name="pais" type="text" value="'.$pais.'"/></td>
					</tr>
					<tr>
						<td class="alineado-izquierda"><label for="cp"><b>CP:</b></label></td>
						<td class="alineado-derecha"><input name="cp" type="text" value="'.$cp.'" maxlength="5"/></td>
					</tr>			
					<tr>
						<td class="alineado-izquierda"><label for="telefono1"><b>Tel�fono 1:</b></label></td>
						<td class="alineado-derecha"><input name="telefono1" type="text" value="'.$telefono1.'"maxlength="11"/></td>
					</tr>			
					<tr>
						<td class="alineado-izquierda"><label for="telefono2"><b>Tel�fono 2:</b></label></td>
						<td class="alineado-derecha"><input name="telefono2" type="text" value="'.$telefono2.'" maxlength="11"/></td>
					</tr>			
					<tr>
						<td class="alineado-izquierda"><label for="fax"><b>Fax:</b></label></td>
						<td class="alineado-derecha"><input name="fax" type="text" value="'.$fax.'" maxlength="11"/></td>
					</tr>			
					<tr>
						<td class="alineado-izquierda"><label for="email"><b>E-mail:</b></label></td>
						<td class="alineado-derecha"><input name="email" type="text" value="'.$email.'"/></td>
					</tr>								
					<tr>
						<td class="alineado-izquierda"></td>
						<td class="alineado-derecha"><input name="enviar" type="submit" value="Enviar" class="boton" /></td> 
					</tr>	
				</table>
			</form>';
	}
?>