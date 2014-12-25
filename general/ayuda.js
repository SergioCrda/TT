function ver_oculta(whichLayer)
{
	if (document.getElementById)
	{
		// this is the way the standards work
		var style2 = document.getElementById(whichLayer).style;
		style2.display = style2.display? "":"none";
	}
	else if (document.all)
	{
		// this is the way old msie versions work
		var style2 = document.all[whichLayer].style;
		style2.display = style2.display? "":"none";
	}
	else if (document.layers)
	{
		// this is the way nn4 works
		var style2 = document.layers[whichLayer].style;
		style2.display = style2.display? "":"none";
	}
}

function muestra(whichLayer, ver){
	if(document.getElementById)
		var style2 = document.getElementById(whichLayer).style;
	else if (document.all)
		var style2 = document.all[whichLayer].style;
	else if (document.layers)		
		var style2 = document.layers[whichLayer].style;
	if(style2.display && ver == 1)
		style2.display = "";
	else if(!ver)
		style2.display = "none";
}

function nueva(pagina){
auten=open(pagina,"pagina","toolbar=no,directories=no,menubar=yes,status=no,scrollbars=yes,resizable=yes,width=700,height=550");
}
function nueva2(pagina){
auten=open(pagina,"certificado","toolbar=yes,directories=no,menubar=no,status=no,scrollbars=yes,resizable=yes,width=700,height=550");
}
