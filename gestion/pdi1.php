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
		<script type="text/javascript">
			//funcion para agregar el evento de selecciona checkbox
			function addEvent(obj,type,fun){  
				if(obj.addEventListener){  
					obj.addEventListener(type,fun,false);  
				}else if(obj.attachEvent){  
					var f=function(){  
						fun.call(obj,window.event);  
					}  
					obj.attachEvent('on'+type,f);  
					obj[fun.toString()+type]=f;  
				}else{  
					obj['on'+type]=fun;  
				}  
			}
			//funcion para habilitar las secciones
			window.onload = function(){
				obj = document.getElementsByName('codigoRamo[]');
				var n = 0;
				for(var i in obj){
					obj[i].num = n++;
					addEvent(obj[i], 'click', function(){
						objFoo1 = document.getElementsByName('seccion[]')[this.num];
						objFoo2 = document.getElementsByName('ramo[]')[this.num];
						if(this.checked){
							objFoo1.disabled = false;
							objFoo2.disabled = false;
						}else{
							objFoo1.disabled = true;
							objFoo2.disabled = true;
						}
					});
				}					
			}
			//funcion para validar los datos
			function validarForm(){
				var x=0;
				var y=0;
				sec=document.getElementsByName('seccion[]');
				cod=document.getElementsByName('codigoRamo[]');
				for(var i=0;i<sec.length;i++){
					if(sec[i].value==""){
						x++;
					}
					if(cod[i].checked){
						y++;
					}
				}
				if(x==sec.length && x==(cod.length-y)){
					alert("Seleccione Ramos y Cantidad de Secciones");
					return false;
				}else{
					if((cod.length-y)<x){
						alert("Seleccione Cantidad de Secciones de todos los Ramos");
						return false;
					}
				}
				return true;
			}
		</script>
		<div id="fecha">
			<?php echo date("d/m/Y"). "<br/>" . date("H:i");?>
		</div>
		<h3>Programaci&oacute;n Docente Inicial<br><small>Selecci&oacute;n de Asignaturas</small></h3>
		<table align="center" cellspacing="0" cellpadding="3" class="pequena" width="75%">
			<tr class="titulo_fila">
				<td>RUT</td>
				<td>Nombre</td>
				<td>Departamento Seleccionado</td>
				<td>Carrera Seleccionada</td>
			</tr>
			<tr class="centro">
				<td>RUT ACADEMICO</td>
				<td>NOMBRE ACADEMICO</td>
				<td>
					<?php
						// Conectando, seleccionando la base de datos
						$link = mysqli_connect('localhost', 'dbttii', 'dbttii', "ttii");
                        if (mysqli_connect_errno()) echo "Falla al conectar con MySQL: " . mysqli_connect_error();

						$depa1 = "SELECT * FROM `departamentos` WHERE `ID_depto` = ".$_POST['departamento_name_id'];
						$depa2 = mysqli_query($link, $depa1) or die('Consulta fallida $depa2: ' . mysqli_error($link));
						while ($depa3 = mysqli_fetch_array($depa2)){
							echo $depa3['Nombre_depto'];
                            $ID_depto = $depa3['ID_depto'];
						}
					?>
				</td>
				<td>
					<?php
						$carre1 = "SELECT * FROM `carreras` WHERE `ID_carrera` = ".$_POST['carrera_name_id'];
						$carre2 = mysqli_query($link, $carre1) or die('Consulta fallida $carre2: ' . mysqli_error($link));
						while ($carre3 = mysqli_fetch_array($carre2)){
                            echo $carre3['Nombre_carrera'];
                            $ID_carrera =  $carre3['ID_carrera'];
						}
					?>
				</td>
			</tr>
		</table>
		<br/>
		<form name="PDI1" method="post" action="pdi2.php" onSubmit="return validarForm()">
			<?php
				echo "<input type='hidden' id='departa' name='depar' value='".$ID_depto."'/>";
				echo "<input type='hidden' id='carrera' name='carre' value='".$ID_carrera."'/>";
				echo "<input type='hidden' id='estaRepetido' name='estaRepetido' value='".$_POST['estaRepetido']."'/>";
				echo "<input type='hidden' id='PDIRepetido' name='PDIRepetido' value='".$_POST['PDIRepetido']."'/>";
			?>
			<table align="center" cellspacing="0" cellpadding="3" class="pequena" width="50%">
				<tr class="titulo_fila">
					<td>Elecci&oacute;n</td>
					<td>C&oacute;digo</td>
					<td>Nombre Ramo</td>
					<td>Secciones</td>
				</tr>
				<?php
                    $repe = $_POST['repetido'];
                    $consultaID_depto1 = "SELECT `ID_depto` FROM `departamentos` WHERE `ID_depto`=".$ID_depto;
                    $consultaID_depto2 = mysqli_query($link, $consultaID_depto1) or die('Consulta fallida $consultaID_depto2: ' . mysqli_error($link));
                    $consultaID_depto3 = mysqli_fetch_assoc($consultaID_depto2);

					$ramo1 = "SELECT * FROM `ramos` WHERE `departamentos_ID_depto`='".$consultaID_depto3['ID_depto']."' ORDER BY `ID_ramo` ASC";
					$ramo2 = mysqli_query($link, $ramo1) or die('Consulta fallida $ramo2: ' . mysqli_error($link));
					while ($ramo3 = mysqli_fetch_array($ramo2)) {
                        echo "<tr class='centro'>";
                        echo "<td><input type='checkbox' id='codigosRamo' name='codigoRamo[]' value='".$ramo3['Codigo_ramo']."'/></td>";
                        echo "<td>".$ramo3['Codigo_ramo']."</td>\n";
                        echo "<td><input type='hidden' id='ramos' name='ramo[]' value='".$ramo3['Nombre_ramo']."' disabled/>".$ramo3['Nombre_ramo']."</td>";
                        echo "<td><input type='number' id='secciones' name='seccion[]' min='1' max='10' disabled/></td>";
                        echo "</tr>";
					}
                    mysqli_close($link);
				?>
			</table>
			<center><br/><input type="submit" name="solicitar" formmethod="post" formaction="pdi2.php" value="Seleccionar periodos de secciones" ></center>
		</form>
	</body>
</html>
