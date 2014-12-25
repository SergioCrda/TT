<html>
	<head>
		<script type="text/javascript">
			/**
			 * By Panino5001: http://forosdelweb.com/miembros/panino5001
			*/ 
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

			/**
			 * By somebody else ;-p
			*/ 
			window.onload = function(){
				obj = document.getElementsByName('enableDisableFoo[]');
				var n = 0;
				for(var i in obj){
					obj[i].num = n++;
					addEvent(obj[i], 'click', function(){
						objFoo = document.getElementsByName('foo[]')[this.num];
						if(this.checked){
							objFoo.disabled = true;
						}else{
							objFoo.disabled = false;
						}
					});
				}
			}
		</script>
	</head>
	<body>
		<table>
			<tr>
				<td><input type="checkbox" name="enableDisableFoo[]" /></td>
				<td><input type="text" name="foo[]" value="foo1" /></td>
			</tr>
			<tr>
				<td><input type="checkbox" name="enableDisableFoo[]" /></td>
				<td><input type="text" name="foo[]" value="foo2" /></td>
			</tr>
			<tr>
				<td><input type="checkbox" name="enableDisableFoo[]" /></td>
				<td><input type="text" name="foo[]" value="foo3" /></td>
			</tr>
			<tr>
				<td><input type="checkbox" name="enableDisableFoo[]" /></td>
				<td><input type="text" name="foo[]" value="foo4" /></td>
			</tr>
			<tr>
				<td><input type="checkbox" name="enableDisableFoo[]" /></td>
				<td><input type="text" name="foo[]" value="foo5" /></td>
			</tr>
		</table>
	</body>
</html>  