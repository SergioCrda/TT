<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' 'http://www.w3.org/TR/html4/loose.dtd'>
<html >
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<title>Creaci&oacute;n de Programaci&oacute;n Docente Inicial</title>
		<link rel="StyleSheet" type="text/css" href="gestion.css">
		<script src="../general/ayuda.js" type="text/javascript" language="JavaScript"></script>
		<script src="../general/prototype.js" type="text/javascript" language="JavaScript"></script>
		<script src="../general/ajax.js" type="text/javascript" language="JavaScript"></script>
	</head>
	<body>
        <script type="text/javascript" language="JavaScript">
			function confirmarForm(){
                return confirm('\u00bfEst\u00e1s seguro de resetear el Proceso PDI-PDF\u003f. \u000ASe eliminar\u00e1n todos los datos de la base de datos');
			}
		</script>
		<div id="fecha">
			<?php echo date("d/m/Y"). "<br/>" . date("H:i");?>
		</div>
		<h3>Configuraci&oacute;n<br><small>Reset de Tablas</small></h3>
        <div>
            <dd>
                <form name="CONFIG" method="post" action="config1.php" onsubmit="return confirmarForm()">
                    <table align="center" border="0" cellpadding="3" cellspacing="0">
                        <tr>
                            <td>
                                <strong>Estimado Vicerrector:</strong><br><br>
                                Desea resetear las tablas de los Procesos de:<br><br>
                                - Programaci&oacute;n Docente Inicial (PDI)<br>
                                - Programaci&oacute;n Docente Final (PDI)<br><br>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align:center">
                                <input type="submit" formaction="config1.php" value="Reset PDI-PDF">
                            </td>
                        </tr>
                    </table>
                </form>
            <dd>
        <div>
	</body>
</html>
