<?php
	include "header.php";
	
	if(!empty($_SESSION[ID_USER])){
		echo "<script>document.location='./'</script>";			
	}
	
	extract($_POST);	
	if(isset($login)){
		$dat=mysql_fetch_assoc(mysql_query("select * from USER WHERE USERNAME='$usn' AND PASSWORD='$pwd'"));
		if(!empty($dat[ID_USER])){
			$_SESSION[ID_USER]=$dat[ID_USER];
			$_SESSION[USERNAME]=$dat[USERNAME];			
			echo "<script>document.location='./'</script>";
		}else{
			echo "<script>alert('Login Gagal');</script>";
		}
	}
?>
<center>
<br><br><br><br>
<h1><font color="white">Login</font></h1><br>
<form method="post">
	<table>
		<tr>
			<td><font color="white">USERNAME</font></td>
			<td><font color="white">:</font></td>
			<td><input type="text" name="usn" placeholder="username"></td>
		</tr>
		<tr>			
			<td><font color="white">PASSWORD</font></td>
			<td><font color="white">:</font></td>
			<td><input type="password" name="pwd" placeholder="***********"></td>
		</tr>
		<tr>
			<td colspan="3" align="right">
				<input type="submit" value="Login" name="login">
			</td>
		</tr>
	
	</table>
</form>
</center>
<?php
	include "footer.php";
?>