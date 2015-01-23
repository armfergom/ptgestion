<?php

function validaFormularioCliente($nombre, $apellidos, $nif, $titulo,$obs, $direccion, $localidad, $provincia, $pais, $cp, $telefono1, $telefono2, $email)
{
	$errores = '';
		
	if ($apellidos == null)
		$errores.= '<li>Apellidos/Empresa es un campo obligatorio.</li>';
	
	/*
	//validamos el nif
	if ($nif != null && !preg_match('/^[0-9]{8}$/',$nif,$gr))
		$errores .= '<li>El NIF ha de ser un número de 8 dígitos</li>';
	*/
		
	//validamos el CP
	if($cp != null)
		if(!preg_match('/^[0-9]{5}$/',$cp,$gr)){
			$errores .= '<li>El código postal ha de ser un número de 5 cifras</li>';
		}
		
	//validamos los teléfonos
	$errores .= validaNumT($telefono1,'Teléfono 1');
	$errores .= validaNumT($telefono2,'Teléfono 2');
	
	//validamos el email
	$errores .= valida_email($email);
	
	return $errores;
}

	function formularioCliente($nombre, $apellidos, $nif, $titulo,$obs, $direccion, $localidad, $provincia, $pais, $cp, $telefono1, $telefono2, $email)
	{
		echo '		
			<form method="post"> 
				<table class="tabla-centrada">
					<tr>
						<td class="alineado-izquierda"><label for="nombre"><b>Nombre:</b></label></td>
						<td class="alineado-derecha"><input name="nombre" type="text" value="'.$nombre.'" /></td>
					</tr>
					<tr>
						<td class="alineado-izquierda"><label for="apellidos"><b>Apellidos/Empresa:</b></label></td>
						<td class="alineado-derecha"><input name="apellidos" type="text" value="'.$apellidos.'" /></td>
					</tr>
					<tr>
						<td class="alineado-izquierda"><label for="nif"><b>NIF:</b></label></td>
						<td class="alineado-derecha"><input name="nif" type="text" maxlength="9" value="'.$nif.'" /></td>
					</tr>
					<tr>
						<td class="alineado-izquierda"><label for="titulo"><b>Título:</b></label></td>
						<td class="alineado-derecha"><select name="titulo">';
		echo '<option value="null"'; if ($titulo == null) echo ' selected="selected"'; echo '></option>';			
		echo '<option value="Sr. D."'; if ($titulo == 'Sr. D.') echo ' selected="selected"'; echo '>Sr. D.</option>';
		echo '<option value="Sra. Dª."'; if ($titulo == 'Sra. Dª.') echo ' selected="selected"'; echo '>Sra. Dª.</option>';
		echo '<option value="Sres. de"'; if ($titulo == 'Sres. de') echo ' selected="selected"'; echo '>Sres. de</option>';
					
		echo '</select></td>
					</tr>
					<tr>
						<td class="alineado-izquierda"><label for="obs"><b>Observaciones:</b></label></td>
						<td class="alineado-derecha"><input name="obs" type="text" value="'.$obs.'" /></td>
					</tr>
					<tr>
						<td class="alineado-izquierda"><label for="direccion"><b>Direccion:</b></label></td>
						<td class="alineado-derecha"><input name="direccion" type="text" value="'.$direccion.'" /></td>
					</tr>
					<tr>
						<td class="alineado-izquierda"><label for="localidad"><b>Localidad:</b></label></td>
						<td class="alineado-derecha"><input name="localidad" type="text" value="'.$localidad.'" /></td>
					</tr>
					<tr>
						<td class="alineado-izquierda"><label for="provincia"><b>Provincia:</b></label></td>
						<td class="alineado-derecha"><input name="provincia" type="text" value="'.$provincia.'" /></td>
					</tr>
					<tr>
						<td class="alineado-izquierda"><label for="pais"><b>País:</b></label></td>
						<td class="alineado-derecha"><input name="pais" type="text" value="'.$pais.'" /></td>
					</tr>
					<tr>
						<td class="alineado-izquierda"><label for="cp"><b>CP:</b></label></td>
						<td class="alineado-derecha"><input name="cp" type="text" maxlength="5" value="'.$cp.'" /></td>
					</tr>			
					<tr>
						<td class="alineado-izquierda"><label for="telefono1"><b>Teléfono 1:</b></label></td>
						<td class="alineado-derecha"><input name="telefono1" type="text" maxlength="11" value="'.$telefono1.'" /></td>
					</tr>			
					<tr>
						<td class="alineado-izquierda"><label for="telefono2"><b>Teléfono 2:</b></label></td>
						<td class="alineado-derecha"><input name="telefono2" type="text" maxlength="11" value="'.$telefono2.'" /></td>
					</tr>				
					<tr>
						<td class="alineado-izquierda"><label for="email"><b>E-mail:</b></label></td>
						<td class="alineado-derecha"><input name="email" type="text" value="'.$email.'" /></td>
					</tr>								
					<tr>
						<td class="alineado-izquierda"></td>
						<td class="alineado-derecha"><input name="enviar" type="submit" value="Enviar" class="boton" /></td> 
					</tr>	
				</table>
			</form>';
	}
	
?>