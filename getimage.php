<?php 
	include_once ('inc/db_connect.php'); 
	
	$articulo = $_REQUEST['articulo'];
	
	$query = "SELECT Imagen FROM articulo WHERE Referencia = $articulo";
	$stmt = $dbh->query($query);
	$row = $stmt->fetch();
	
	//you may opt to store the mime type in the DB somewhere
	header("Content-Type: image");
	die($row['Imagen']); 
?>