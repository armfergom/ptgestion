
function actualiza(){
	var temp;
	temp = document.getElementById("examinarMal").value;
	document.getElementById("upload_value").value=temp;;
}

function aclaracionArticulos(){
	document.getElementById("aclaracion").innerHTML = "<p>Gestión de artículos.</p>";
	document.getElementById("botonArticulo").className="botonMPP";
}
function aclaracionArticulos2(){
	document.getElementById("aclaracion").innerHTML = "<p><br/></p>";
	document.getElementById("botonArticulo").className="botonMP";
}



function aclaracionClientes(){
	document.getElementById("aclaracion").innerHTML = "<p>Gestión de clientes.</p>";
	document.getElementById("botonCliente").className="botonMPP";
}
function aclaracionClientes2(){
	document.getElementById("aclaracion").innerHTML = "<p><br/></p>";
	document.getElementById("botonCliente").className="botonMP";
}


function aclaracionProveedores(){
	document.getElementById("aclaracion").innerHTML = "<p>Gestión de proveedores.</p>";
	document.getElementById("botonProveedor").className="botonMPP";
}
function aclaracionProveedores2(){
	document.getElementById("aclaracion").innerHTML = "<p><br/></p>";
	document.getElementById("botonProveedor").className="botonMP";
}


function aclaracionCompras(){
	document.getElementById("aclaracion").innerHTML = "<p>Gestión de compras.</p>";
	document.getElementById("botonCompra").className="botonMPP";
}
function aclaracionCompras2(){
	document.getElementById("aclaracion").innerHTML = "<p><br/></p>";
	document.getElementById("botonCompra").className="botonMP";
}


function aclaracionVentas(){
	document.getElementById("aclaracion").innerHTML = "<p>Gestión de ventas.</p>";
	document.getElementById("botonVenta").className="botonMPP";
}
function aclaracionVentas2(){
	document.getElementById("aclaracion").innerHTML = "<p><br/></p>";
	document.getElementById("botonVenta").className="botonMP";
}


function aclaracionPresupuestos(){
	document.getElementById("aclaracion").innerHTML = "<p>Gestión de presupuestos.</p>";
	document.getElementById("botonPresupuesto").className="botonMPP";
}
function aclaracionPresupuestos2(){
	document.getElementById("aclaracion").innerHTML = "<p><br/></p>";
	document.getElementById("botonPresupuesto").className="botonMP";
}


function aclaracionListas(){
	document.getElementById("aclaracion").innerHTML = "<p>Gestión de listas de boda.</p>";
	document.getElementById("botonLista").className="botonMPP";
}
function aclaracionListas2(){
	document.getElementById("aclaracion").innerHTML = "<p><br/></p>";
	document.getElementById("botonLista").className="botonMP";
}


function aclaracionOtras(){
	document.getElementById("aclaracion").innerHTML = "<p>Gestión de otras consultas.</p>";
	document.getElementById("botonOtra").className="botonMPP";
}
function aclaracionOtras2(){
	document.getElementById("aclaracion").innerHTML = "<p><br/></p>";
	document.getElementById("botonOtra").className="botonMP";
}


function aclaracionAltas(){
	document.getElementById("aclaracionA").innerHTML = "<p>Formulario para dar de alta nuevos artículos.</p>";
	document.getElementById("botonAltaArt").className="botonMPP";
}
function aclaracionAltas2(){
	document.getElementById("aclaracionA").innerHTML = "<p><br/></p>";
	document.getElementById("botonAltaArt").className="botonMP";
}



function aclaracionListadosArt(){
	document.getElementById("aclaracionA").innerHTML = "<p>Lista ordenada por referencia de artículos.</p>";
	document.getElementById("botonListadoArt").className="botonMPP";
}
function aclaracionListadosArt2(){
	document.getElementById("aclaracionA").innerHTML = "<p><br/></p>";
	document.getElementById("botonListadoArt").className="botonMP";
}



function aclaracionBusquedaArt(){
	document.getElementById("aclaracionA").innerHTML = "<p>Búsqueda por referencia y por nombre de artículos. Modificaciones y borrados.</p>";
	document.getElementById("botonBusquedaArt").className="botonMPP";
}
function aclaracionBusquedaArt2(){
	document.getElementById("aclaracionA").innerHTML = "<p><br/></p>";
	document.getElementById("botonBusquedaArt").className="botonMP";
}



function aclaracionInventario(){
	document.getElementById("aclaracionA").innerHTML = "<p>Inventario por proveedores. Becara y otros proveedores.</p>";
	document.getElementById("botonInventario").className="botonMPP";
}
function aclaracionInventario2(){
	document.getElementById("aclaracionA").innerHTML = "<p><br/></p>";
	document.getElementById("botonInventario").className="botonMP";
}


function aclaracionAltasProv(){
	document.getElementById("aclaracionP").innerHTML = "<p>Formulario para dar de alta nuevos proveedores.</p>";
	document.getElementById("botonAltaProv").className="botonMPP";
}
function aclaracionAltasProv2(){
	document.getElementById("aclaracionP").innerHTML = "<p><br/></p>";
	document.getElementById("botonAltaProv").className="botonMP";
}



function aclaracionListadosProv(){
	document.getElementById("aclaracionP").innerHTML = "<p>Lista ordenada alfabéticamente de los proveedores.</p>";
	document.getElementById("botonListadoProv").className="botonMPP";
}
function aclaracionListadosProv2(){
	document.getElementById("aclaracionP").innerHTML = "<p><br/></p>";
	document.getElementById("botonListadoProv").className="botonMP";
}



function aclaracionBusquedaProv(){
	document.getElementById("aclaracionP").innerHTML = "<p>Búsqueda por nombre de proveedores. Modificaciones y borrados.</p>";
	document.getElementById("botonBusquedaProv").className="botonMPP";
}
function aclaracionBusquedaProv2(){
	document.getElementById("aclaracionP").innerHTML = "<p><br/></p>";
	document.getElementById("botonBusquedaProv").className="botonMP";
}

function aclaracionAltaCompra(){
	document.getElementById("aclaracionC").innerHTML = "<p>Formulario para dar de alta nuevas compras.</p>";
	document.getElementById("botonAltaCompra").className="botonMPP";
}
function aclaracionAltaCompra2(){
	document.getElementById("aclaracionC").innerHTML = "<p><br/></p>";
	document.getElementById("botonAltaCompra").className="botonMP";
}



function aclaracionListadoCompras(){
	document.getElementById("aclaracionC").innerHTML = "<p>Lista de compras ordenada cronológicamente.</p>";
	document.getElementById("botonListadoCompras").className="botonMPP";
}
function aclaracionListadoCompras2(){
	document.getElementById("aclaracionC").innerHTML = "<p><br/></p>";
	document.getElementById("botonListadoCompras").className="botonMP";
}




function aclaracionBusquedaRef(){
	document.getElementById("aclaracionT").innerHTML = "<p>Búsqueda por la referencia del artículo.</p>";
	document.getElementById("botonBusquedaRef").className="botonMPP";
}
function aclaracionBusquedaRef2(){
	document.getElementById("aclaracionT").innerHTML = "<p><br/></p>";
	document.getElementById("botonBusquedaRef").className="botonMP";
}



function aclaracionBusquedaNom(){
	document.getElementById("aclaracionT").innerHTML = "<p>Búsqueda por el nombre del artículo.</p>";
	document.getElementById("botonBusquedaNom").className="botonMPP";
}
function aclaracionBusquedaNom2(){
	document.getElementById("aclaracionT").innerHTML = "<p><br/></p>";
	document.getElementById("botonBusquedaNom").className="botonMP";
}

function aclaracionAltasCli(){
	document.getElementById("aclaracionC").innerHTML = "<p>Formulario para dar de alta nuevos clientes.</p>";
	document.getElementById("botonAltaCli").className="botonMPP";
}
function aclaracionAltasCli2(){
	document.getElementById("aclaracionC").innerHTML = "<p><br/></p>";
	document.getElementById("botonAltaCli").className="botonMP";
}



function aclaracionListadosCli(){
	document.getElementById("aclaracionC").innerHTML = "<p>Lista ordenada alfabéticamente de los clientes.</p>";
	document.getElementById("botonListadoCli").className="botonMPP";
}
function aclaracionListadosCli2(){
	document.getElementById("aclaracionC").innerHTML = "<p><br/></p>";
	document.getElementById("botonListadoCli").className="botonMP";
}



function aclaracionBusquedaCli(){
	document.getElementById("aclaracionC").innerHTML = "<p>Búsqueda por nombre de clientes. Modificaciones y borrados.</p>";
	document.getElementById("botonBusquedaCli").className="botonMPP";
}
function aclaracionBusquedaCli2(){
	document.getElementById("aclaracionC").innerHTML = "<p><br/></p>";
	document.getElementById("botonBusquedaCli").className="botonMP";
}


function aclaracionEtiquetas(){
	document.getElementById("aclaracionA").innerHTML = "<p>Generar etiquetas.</p>";
	document.getElementById("botonEtiquetas").className="botonMPP";
}
function aclaracionEtiquetas2(){
	document.getElementById("aclaracionA").innerHTML = "<p><br/></p>";
	document.getElementById("botonEtiquetas").className="botonMP";
}

function aclaracionTicket(){
	document.getElementById("aclaracionV").innerHTML = "<p>Ventas con ticket.</p>";
	document.getElementById("botonTicket").className="botonMPP";
}
function aclaracionTicket2(){
	document.getElementById("aclaracionV").innerHTML = "<p><br/></p>";
	document.getElementById("botonTicket").className="botonMP";
}

function aclaracionFactura(){
	document.getElementById("aclaracionV").innerHTML = "<p>Ventas con factura.</p>";
	document.getElementById("botonFactura").className="botonMPP";
}
function aclaracionFactura2(){
	document.getElementById("aclaracionV").innerHTML = "<p><br/></p>";
	document.getElementById("botonFactura").className="botonMP";
}


function aclaracionListadoVentas(){
	document.getElementById("aclaracionV").innerHTML = "<p>Listado de ventas. Tanto de tickets como de facturas.</p>";
	document.getElementById("botonListadoVentas").className="botonMPP";
}
function aclaracionListadoVentas2(){
	document.getElementById("aclaracionV").innerHTML = "<p><br/></p>";
	document.getElementById("botonListadoVentas").className="botonMP";
}

function aclaracionListadoVentasTicket(){
	document.getElementById("aclaracionV").innerHTML = "<p>Listado de ventas de tickets.</p>";
	document.getElementById("botonListadoVentasTicket").className="botonMPP";
}
function aclaracionListadoVentasTicket2(){
	document.getElementById("aclaracionV").innerHTML = "<p><br/></p>";
	document.getElementById("botonListadoVentasTicket").className="botonMP";
}

function aclaracionListadoVentasFactura(){
	document.getElementById("aclaracionV").innerHTML = "<p>Listado de ventas de facturas.</p>";
	document.getElementById("botonListadoVentasFactura").className="botonMPP";
}
function aclaracionListadoVentasFactura2(){
	document.getElementById("aclaracionV").innerHTML = "<p><br/></p>";
	document.getElementById("botonListadoVentasFactura").className="botonMP";
}

function aclaracionListadoVentas(){
	document.getElementById("aclaracionV").innerHTML = "<p>Listado de ventas. Tanto de facturas como de tickets.</p>";
	document.getElementById("botonListadoVentas").className="botonMPP";
}
function aclaracionListadoVentas2(){
	document.getElementById("aclaracionV").innerHTML = "<p><br/></p>";
	document.getElementById("botonListadoVentas").className="botonMP";
}

function aclaracionAltaPresupuesto(){
	document.getElementById("aclaracionV").innerHTML = "<p>Altas de presupuestos.</p>";
	document.getElementById("botonAltaPresupuesto").className="botonMPP";
}
function aclaracionAltaPresupuesto2(){
	document.getElementById("aclaracionV").innerHTML = "<p><br/></p>";
	document.getElementById("botonAltaPresupuesto").className="botonMP";
}

function aclaracionListadoPresupuestos(){
	document.getElementById("aclaracionV").innerHTML = "<p>Listado de presupuestos.</p>";
	document.getElementById("botonListadoPresupuestos").className="botonMPP";
}

function aclaracionListadoPresupuestos2(){
	document.getElementById("aclaracionV").innerHTML = "<p><br/></p>";
	document.getElementById("botonListadoPresupuestos").className="botonMP";
}

function aclaracionAltaListaBoda(){
	document.getElementById("aclaracionV").innerHTML = "<p>Altas de listas de bodas.</p>";
	document.getElementById("botonAltaListaBoda").className="botonMPP";
}
function aclaracionAltaListaBoda2(){
	document.getElementById("aclaracionV").innerHTML = "<p><br/></p>";
	document.getElementById("botonAltaListaBoda").className="botonMP";
}

function aclaracionListadoListasBoda(){
	document.getElementById("aclaracionV").innerHTML = "<p>Listado de listas de bodas.</p>";
	document.getElementById("botonListadoListasBoda").className="botonMPP";
}

function aclaracionListadoListasBoda2(){
	document.getElementById("aclaracionV").innerHTML = "<p><br/></p>";
	document.getElementById("botonListadoListasBoda").className="botonMP";
}

function aclaracionTicketBec(){
	document.getElementById("botonTicketBec").className="botonMPP";
}

function aclaracionTicketBec2(){
	document.getElementById("botonTicketBec").className="botonMP";
}

function aclaracionTicketPT(){
	document.getElementById("botonTicketPT").className="botonMPP";
}

function aclaracionTicketPT2(){
	document.getElementById("botonTicketPT").className="botonMP";
}

function esIntangible(referencia)
{
	return referencia == 97420001
		|| referencia == 97420002
		|| referencia == 97420003
		|| referencia == 97420004
		|| referencia == 97420005
		|| referencia == 97420006
		|| referencia == 97420007
		|| referencia == 97420008
		|| referencia == 97420009
		|| referencia == 97420010
		|| referencia == 97420011
		|| referencia == 97420012
		|| referencia == 97420013
		|| referencia == 97420030	
		|| referencia == 97410001
		|| referencia == 97410002
		|| referencia == 97410003
		|| referencia == 97410004
		|| referencia == 97410005
		|| referencia == 97410006
		|| referencia == 97410007
		|| referencia == 97410008
		|| referencia == 97410009
		|| referencia == 97410010
		|| referencia == 97410011
		|| referencia == 97410012
		|| referencia == 97410013
		|| referencia == 97410014
		|| referencia == 97410015
		|| referencia == 97410020
		|| referencia == 97410021
		|| referencia == 97410022
		|| referencia == 97410023
		|| referencia == 97410024
		|| referencia == 97410025
		|| referencia == 97410026
		|| referencia == 97400001	
		|| referencia == 93700001	
		|| referencia == 97400002
		|| referencia == 97400003
		|| referencia == 97400004
		|| referencia == 97400005
		|| referencia == 97400006
		|| referencia == 97400007
		|| referencia == 97400008
		|| referencia == 97400009			
		|| referencia == 97400010
		|| referencia == 97400011
		|| referencia == 97400012
		|| referencia == 97400013
		|| referencia == 97400014
		|| referencia == 97400015
		|| referencia == 97500001
		|| referencia == 97500002
		|| referencia == 11100032
		|| referencia == 11100035
		|| referencia == 82200003
		|| referencia == 98200003
		|| referencia == 98200004
		|| referencia == 98200005
		|| referencia == 98200006
		|| referencia == 98200007
		|| referencia == 98200008
		|| referencia == 98200009;
}