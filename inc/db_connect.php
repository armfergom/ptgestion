<?php
  
function connect() {
	
	$dbh = null;
    $hostname = 'localhost';
    $username = 'PTUser';
    $password = '3jj9STdSdKE2GS7w';
    $dbname = 'PTGestion';
    
    try {
        $dbh = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
		return $dbh;
    }
    catch (PDOException $e) {
        die('No se pudo establecer la conexión a la base de datos<br />Por favor, contacte con el administrador');
    }
    
    return false;
}

function disconnect($dbh) {

    $dbh = null;
}    

	$dbh = connect();
 
?>