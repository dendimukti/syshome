<?php
	include "header.php";
	extract($_POST);
	if(isset($insmodul)){
		if(strlen($token)==10){		
			$tk=mysql_query("SELECT * FROM MODUL WHERE TOKEN='$token'");
			$jml=mysql_num_rows($tk);
			if($jml==0){
				mysql_query("INSERT INTO MODUL VALUES('','$token','-','0','$desc','0')");
				echo "<script>alert('data modul pengendali telah terinput');</script>";
				echo "<script>document.location='./modul';</script>";
			}
			else{
				echo "<script>alert('Invalid Token');</script>";
				echo "<script>document.location='./modul_add';</script>";			
			}
			//header("./../modul");	
		}
		else{
			echo "<script>alert('Isi form dengan benar');</script>";
		}
	}
?>

<link href="jquery-ui-1.8.1.custom/css/custom-theme/jquery-ui-1.8.1.custom.css" rel="stylesheet" type="text/css" />
<script src="jquery-ui-1.8.1.custom/js/jquery-1.4.2.min.js"></script>
<script src="jquery-ui-1.8.1.custom/js/jquery-ui-1.8.1.custom.min.js"></script>

<center>
<font color="white">
<h2> Modul Pengendali Baru</h2>

	<a href='./modul'><img src=icon/back.png id=baten></a>

<form method="post">
	<table>
		<tr>
			<td>Token Modul Pengendali</td>
			<td>:</td>
			<td><input type="text" name="token" maxlength="10" placeholder="10 Karakter"></td>
		</tr>
		<tr>
			<td>Deskripsi</td>
			<td>:</td>
			<td><textarea name="desc"></textarea></td>
		</tr>
		<tr>
			<td colspan="3"><input type="submit" name="insmodul"></td>
		</tr>
	</table>
</form>
</font>
</center>

<?php
	include "footer.php";
?>