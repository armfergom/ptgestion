<?php
	
	include_once('inc/header.php');
	if(isset($_REQUEST['idProveedor']))
	{
		$Id = $_REQUEST['idProveedor'];
	
		try 
		{
			$query = "SELECT IdProveedor,Nombre,Direccion,Localidad,Provincia,Pais,CP,Tlf1,Tlf2,Fax,Email FROM proveedor WHERE IdProveedor=$Id";
			$stmt = $dbh->query($query);							
			
			if ($stmt->rowCount() == 1)
			{
				$row = $stmt->fetch();
				
				echo '
				<a href="menuProveedores.php"><h4>Menú proveedores</h4></a>
					<table class="tabla-centrada">
					<tr>
					<td><a href="modificarProveedor.php?idProveedor='.$Id.'"><button type="button" class="boton" id="botonModificarProv">Modificar</button></td>
					<td><a href="borrarProveedor.php?idProveedor='.$Id.'"><button type="button" class="boton" id="botonBorrarProv" >Borrar</button></a></td>
					</tr>
					</table>
					<br />
				';
				
				echo '<table class="tabla-listado">';
			
				echo '<tr class="filaImpar"><td>Nombre</td><td>Direccion</td><td>Localidad</td><td>Provincia</td><td>Pais</td><td>CP</td></tr>';
				
				echo '<tr class="filaPar">';
				
				echo '<td>'.$row['Nombre'].'</td>';
				
				if ($row['Direccion'] != null){
					echo '<td>'.$row['Direccion'].'</td>';
				}
				else{
					echo '<td>---</td>';
				}
				
				if ($row['Localidad'] != null)
					echo '<td>'.$row['Localidad'].'</td>';
				else
					echo '<td>---</td>';
					
				if ($row['Provincia'] != null)
					echo '<td>'.$row['Provincia'].'</td>';
				else
					echo '<td>---</td>';
					
				if ($row['Pais'] != null)
					echo '<td>'.$row['Pais'].'</td>';
				else
					echo '<td>---</td>';
					
				if ($row['CP']!=0)
					echo '<td>'.$row['CP'].'</td>';
				else
					echo '<td>---</td>';
				
				echo '</tr>';
				
				echo '</table>';
				
				echo '<br/>';
				
				echo '<table class="tabla-listado">';
			
				echo '<tr class="filaImpar"><td>Teléfono1</td><td>Teléfono2</td><td>Fax</td><td>Email</td></tr>';
				
				echo '<tr class="filaPar">';
				
				if($row['Tlf1']!=0)
					echo '<td>'.$row['Tlf1'].'</td>';
				else
					echo '<td>---</td>';
					
				if ($row['Tlf1'] !=0)
					echo '<td>'.$row['Tlf2'].'</td>';
				else
					echo '<td>---</td>';
					
				if ($row['Fax'] !=0)
					echo '<td>'.$row['Fax'].'</td>';
				else
					echo '<td>---</td>';
					
				if ($row['Email'] !=null)
					echo '<td>'.$row['Email'].'</td>';
				else
					echo '<td>---</td>';
					
				echo '</tr>';
			
				echo '</table>';
			}
		}
		catch(PDOException $e ) {
			// tratamiento del error
			die("Error PDO: ".$e->GetMessage());
		}
	}

	include_once('inc/footer.php');

?>