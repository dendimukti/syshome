<?php
	include "header.php";
	extract($_POST);
	if(isset($updmodul) && !empty($token)){
		mysql_query("UPDATE MODUL SET TOKEN='$token', EXPL='$desc' WHERE ID_MODUL='$id'");
		//echo "UPDATE MODUL SET IP_ADDR='$ip', EXPL='$desc' WHERE ID_MODUL='$id'";
		echo "<script>alert('data modul pengendali telah terupdate');</script>";
		echo "<script>document.location='./modul';</script>";
		//header("./../modul");
	}
	extract($_GET);
	$que=mysql_query("SELECT * FROM MODUL WHERE ID_MODUL='$id'");
	$data=mysql_fetch_assoc($que);
?>

<center>
<font color="white">
<h2> Update Modul Pengendali </h2>
	<a href='./modul'><img src=icon/back.png id=baten></a>

<form method="post">
	<input type="hidden" name="id" value="<?php echo $data["ID_MODUL"]; ?>">
	<table>
		<tr>
			<td>Token Modul Pengendali</td>
			<td>:</td>
			<td><input type="text" name="token" value="<?php echo $data["TOKEN"]; ?>" readonly=""></td>
		</tr>
		<tr>
			<td>Deskripsi</td>
			<td>:</td>
			<td><textarea name="desc"><?php echo $data["EXPL"]; ?></textarea></td>
		</tr>
		<tr>
			<td colspan="3"><input type="submit" name="updmodul"></td>
		</tr>
	</table>
</form>
</font>
</center>

<?php
	include "footer.php";
?>