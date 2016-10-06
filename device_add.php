<?php
	include "header.php";
	extract($_POST);
	if(isset($insdevice)){
		if(!empty($nama)){
			if(!is_numeric($daya)) $daya=0;
			mysql_query("INSERT INTO DEVICE_LIST VALUES('','$nama','$daya','$desc','0')");
			echo "<script>alert('data piranti elektronik telah terinput');</script>";
			echo "<script>document.location='./device';</script>";
			//header("./../modul");
		}
		else{
			echo "<script>alert('Isi form dengan benar');</script>";
		}
	}
?>
<script>
function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
</script>
<center>
<font color="white">
<h2> Piranti Elektronik Baru</h2>
<a href='./device'><img src=icon/back.png id=baten></a>
	 	
<form method="post">
	<table>
		<tr>
			<td>Nama Piranti Elektronik</td>
			<td>:</td>
			<td><input type="text" name="nama" placeholder="Device Name"></td>
		</tr>
		<tr>
			<td>Daya (Optional)</td>
			<td>:</td>
			<td><input type="text" name="daya" placeholder="__ Watt" onkeypress="return isNumber(event)"></td>
		</tr>
		<tr>
			<td>Deskripsi</td>
			<td>:</td>
			<td><textarea name="desc"></textarea></td>
		</tr>
		<tr>
			<td colspan="3"><input type="submit" name="insdevice"></td>
		</tr>
	</table>
</form>
</font>
</center>

<?php
	include "footer.php";
?>