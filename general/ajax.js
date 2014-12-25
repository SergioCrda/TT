function nuevoAjax(){
  var xmlhttp=false;
  try {
   // Creación del objeto ajax para navegadores diferentes a Explorer
   xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
  } catch (e) {
   // o bien
   try {
     // Creación del objet ajax para Explorer
     xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); } catch (E) {
     xmlhttp = false;
   }
  }

  if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
   xmlhttp = new XMLHttpRequest();
  }
  return xmlhttp;
}


function cargarContenido(d1, sitio){
var d1,sitio;
sitio = document.getElementById(sitio);
ajax=nuevoAjax();
ajax.open("GET", d1,true);
ajax.onreadystatechange=function() {
if (ajax.readyState == 4)	{
		if(ajax.status == 200)		{
		  sitio.innerHTML = ajax.responseText;
		}
	}
	else	{
		 sitio.innerHTML = "<center><img src='../images/cargando.gif'></center>";
	}
}
ajax.send(null)
}

function cargarSelect(select, apunta, div)
{
var apunta;
div = document.getElementById(div);
select = select.options[select.selectedIndex].value; 
ajax=nuevoAjax();
ajax.open("GET", apunta+select,true);
ajax.onreadystatechange=function() {
if (ajax.readyState == 4)
	{
		if(ajax.status == 200)
		{
		  div.innerHTML = ajax.responseText;
		}
	}
	else
	{
		 div.innerHTML = "<center>Cargando...<img src='../images/cargando.gif'></center>";
	}
}
ajax.send(null);
}

function cambia(form)
{
var arreglo = new String();
for(i = 0; i < form.elements.length; i++)
{
	 if(i > 0) 
		arreglo = arreglo + "&";
	arreglo = arreglo+form.elements[i].name+"="+form.elements[i].value;
}
return arreglo;
}

function cargaPOST(url, div, form){
var div, paso;
div = document.getElementById(div);
ajax=nuevoAjax();
ajax.open("POST", url, true);
paso = cambia(form);
ajax.onreadystatechange=function() {
if (ajax.readyState == 4)
	{
		if(ajax.status == 200)
		{
		  div.innerHTML = ajax.responseText;
		}
	}
	else
	{
		 div.innerHTML = "<center>Cargando...<img src='../images/cargando.gif'></center>";
	}
}
ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
ajax.send(paso);
}
