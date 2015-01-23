<?php

function valida_email($email)
{
    $error = '';
	//Valida el email
	if($email != null){
		if (!preg_match('/^(.+)@(.+)\.([A-Za-z]{2,3})$/', $email))
		{
			$error .= "<li>E-mail no válido.</li>";
		}
	}
	
	return $error;

}
 
function validaNumT($num,$c)
{
	 $error = '';
	//Valida el número de teléfono
	if($num != 0)
		if (!preg_match('/^[0-9]{9,}$/', $num))
		{
			$error .= "<li>$c no válido.</li>";
		}
	
	return $error;

}
?>