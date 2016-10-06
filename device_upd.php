<?php
	include "header.php";
	extract($_POST);
	if(isset($upddevice)){
		if(!empty($nama)){
			if(!is_numeric($daya)) $daya=0;
			mysql_query("UPDATE DEVICE_LIST SET DEVICE_NAME='$nama', POWER='$daya', EXPL='$desc' WHERE ID_DEVICE='$id'");
			echo "<script>alert('data piranti elektronik telah terupdate');</script>";
			echo "<script>document.location='./device';</script>";
			//header("./../modul");			
		}
		else{
			echo "<script>alert('Isi form dengan benar');</script>";
		}
	}
	extract($_GET);
	$que=mysql_query("SELECT * FROM DEVICE_LIST WHERE ID_DEVICE='$id'");
	$data=mysql_fetch_assoc($que);
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
<h2> Update Piranti Elektronik</h2>
	
					<a href='./device'><img src=icon/back.png id=baten></a>
	
<form method="post">
	<input type="hidden" name="id" value="<?php echo $data["ID_DEVICE"]; ?>">
	<table>
		<tr>
			<td>Nama Piranti Elektronik</td>
			<td>:</td>
			<td><input type="text" name="nama" placeholder="Device Name" value="<?php echo $data["DEVICE_NAME"]; ?>"></td>
		</tr>
		<tr>
			<td>Daya (Optional)</td>
			<td>:</td>
			<td><input type="text" name="daya" placeholder="__ Watt" value="<?php echo $data["POWER"]; ?>" onkeypress="return isNumber(event)"></td>
		</tr>
		<tr>
			<td>Deskripsi</td>
			<td>:</td>
			<td><textarea name="desc"><?php echo $data["EXPL"]; ?></textarea></td>
		</tr>
		<tr>
			<td colspan="3"><input type="submit" name="upddevice"></td>
		</tr>
	</table>
</form>
</font>
</center>

<?php
	include "footer.php";
?>