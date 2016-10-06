<?php
	include "header.php";		
	$do=$_REQUEST['do'];
		if($do=="del"){
			$kode=$_REQUEST['kode'];
			mysql_query("UPDATE DEVICE_LIST SET DEL='1' WHERE ID_DEVICE='$kode'");
			echo "<script>document.location=\"./device\"</script>";
		}
?>
	<center>
<font color="white">
<h2> Data Master Piranti Elektronik</h2>
</font>
<br>
	<a href='./device_add.php'><img src=icon/add.png id=baten></a>

		<?php
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
		<table align="center" width="70%">
			<tr align=center bgcolor="#FF9933">
				<td width="5%">No.</td>
				<td width="30%">Nama Piranti Elektronik</td>
				<td width="20%">Spesifikasi Daya</td>
				<td width="35%">Deskripsi</td>
				<td width="10%" colspan="2">Aksi</td>
			</tr>
		<?php
		$nomer=$hl*10;
		$que=mysql_query("SELECT * FROM DEVICE_LIST WHERE DEL='0'");
		if(mysql_num_rows($que)>0){
			while($dev=mysql_fetch_array($que)){
				$nomer=$nomer+1;
				$mod=$nomer%2;
				if($mod==1)$color="#CCCCCC";
				else $color="#eaeced";
				echo "<tr align=center bgcolor=$color>
				<td>$nomer</td>
				";
				echo "
				<td>$dev[DEVICE_NAME]</td>
				<td>";
				if($dev[POWER]>0)
					echo $dev[POWER]." WATT";
				else
					echo "Tidak Disetting";
				echo "</td>
				<td>$dev[EXPL]</td>";
				echo "
				<td><a href=\"./device_upd?id=$dev[ID_DEVICE]\"><img src=\"./icon/modify.png\" alt=\"Edit\"></a></td>
				<td><a href=\"./device?do=del&kode=$dev[ID_DEVICE]\"><img src=\"./icon/delete.png\" alt=\"Hapus\"></a></td>
				</tr>";
			}
		}
		else
			echo "<tr><td colspan='6' align='center'>Tidak ada data</td></tr>";
		

		?>
		</table>	
	</center>
<?php
	include "footer.php";
?>