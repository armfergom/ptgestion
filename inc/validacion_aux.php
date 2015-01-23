<?php

function valida_email($email)
{
    $error = '';
	//Valida el email
	if($email != null){
		if (!preg_match('/^(.+)@(.+)\.([A-Za-z]{2,3})$/', $email))
		{
			$error .= "<li>E-mail no v�lido.</li>";
		}
	}
	
	return $error;

}
 
function validaNumT($num,$c)
{
	 $error = '';
	//Valida el n�mero de tel�fono
	if($num != 0)
		if (!preg_match('/^[0-9]{9,}$/', $num))
		{
			$error .= "<li>$c no v�lido.</li>";
		}
	
	return $error;

}
?>