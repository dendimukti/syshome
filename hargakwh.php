<?php
	include "header.php";
	extract($_POST);
	if(isset($insprice)){
		if(!empty($harga)){
			mysql_query("INSERT INTO PRICE VALUES('','$harga',now())");
			echo "<script>alert('data harga baru telah terupdate');</script>";
			echo "<script>document.location='./hargakwh';</script>";
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
<h2> Data Master Harga Listrik / KWH</h2>
<br>
<form method="post">
	<table>
		<tr>
			<td>Harga / KWH Baru</td>
			<td>:</td>
			<td><input type="text" name="harga" placeholder="Harga" onkeypress="return isNumber(event)"></td>
		</tr>
		<tr>
			<td colspan="3" align="right"><input type="submit" name="insprice"></td>
		</tr>
	</table>
</form>
</font>	
<br>
		<?php
//		<a href=./price_add><img src=icon/add.png id=baten></a>		
//		$hl=$_REQUEST[hl];
//		echo "Halaman : ";
//		$jml=mysql_num_rows(mysql_query("select * from vendor"));
//		$mod=$jml%10;
//		if($mod>0)
//			$hlm=($jml-$mod)/10+1;
//		else
//			$hlm=$jml/10;
//		for($i=0;$i<$hlm;$i++){
//			$link=$i+1;
//			if($hl==$i)
//				echo $link."&nbsp;&nbsp;";
//			else
//				echo "<a href=?page=navvendor&hl=$i>".$link."&nbsp;&nbsp;</a>";
//		}
		?>
		<table align="center" width="30%">
			<tr align=center bgcolor="#FF9933">
				<td width="20%">No.</td>
				<td width="30">Harga</td>
				<td width="50%">Last Update</td>
			</tr>
		<?php
		$nomer=$hl*10;
		$que=mysql_query("SELECT * FROM PRICE ORDER BY LAST_UPD DESC limit ".$nomer.",10");
		if(mysql_num_rows($que)>0){
			while($price=mysql_fetch_array($que)){
				$nomer=$nomer+1;
				$mod=$nomer%2;
				if($mod==1)$color="#CCCCCC";
				else $color="#eaeced";
				
				$price[KWH_PRICE]="Rp. ".$price[KWH_PRICE];
				if($nomer==1){
					//$nomer="<b>".$nomer."</b>";
					$price[KWH_PRICE]="<b>".$price[KWH_PRICE]."</b>";
					$price[LAST_UPD]="<b>".$price[LAST_UPD]."</b>";
				}
				
				echo "<tr align=center bgcolor=$color>
					<td>$nomer</td>
					<td>$price[KWH_PRICE]</td>
					<td>$price[LAST_UPD]</td>
				</tr>";
				//<td><a href=\"?page=price_upd&kode=$price[ID_PRICE]\"><img src=\"./icon/delete.png\" alt=\"Hapus\"></a></td>
			}
		}
		else
			echo "<tr><td colspan='3' align='center'>Tidak ada data</td></tr>";
		
//		$do=$_REQUEST['do'];
//		if($do=="del"){
//			$kode=$_REQUEST['kode'];
//			mysql_query("DELETE FROM PRICE WHERE ID_PRICE='$kode'");
//			echo "<script>document.location=\"?page=hargakwh\"</script>";
//		}
		?>
		</table>	
	</center>
<?php
	include "footer.php";
?>