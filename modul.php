<?php
	include "header.php";
	
		$do=$_REQUEST['do'];
		if($do=="del"){
			$kode=$_REQUEST['kode'];
			mysql_query("UPDATE MODUL SET DEL='1', STATUS='0' WHERE ID_MODUL='$kode'");
			echo "<script>document.location=\"./modul\"</script>";
		}
?>

	<center>
<font color="white">
<h2> Data Master Modul Pengendali</h2>
</font>
	<br>
	<a href='./modul_add'><img src=icon/add.png id=baten></a>
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
		<table align="center" width="80%">
			<tr align=center bgcolor="#FF9933">
				<td width="5%">No.</td>
				<td width="20">Token</td>
				<td width="20">IP Address</td>
				<td width="10%">Status</td>
				<td width="30%">Deskripsi</td>
				<td width="10%" colspan="2">Aksi</td>
			</tr>
		<?php
		$nomer=$hl*10;
		$que=mysql_query("SELECT * FROM MODUL WHERE DEL='0' ORDER BY STATUS ASC limit ".$nomer.",10");
		if(mysql_num_rows($que)>0){
			while($modul=mysql_fetch_array($que)){
				$nomer=$nomer+1;
				$mod=$nomer%2;
				if($mod==1)$color="#CCCCCC";
				else $color="#eaeced";
				
				echo "<tr align=center bgcolor=$color>
				<td>$nomer</td>
				<td>$modul[TOKEN]</td>
				<td>$modul[IP_ADDR]</td>
				<td>";
				if($modul[STATUS]==0) echo "Nonaktif";
				else if($modul[STATUS]==1) echo "Aktif";
				else if($modul[STATUS]==2) echo "Terblokir";
				echo "</td>
				<td>$modul[EXPL]</td>";
				echo "
				<td><a href=\"./modul_upd?id=$modul[ID_MODUL]\"><img src=\"./icon/modify.png\" alt=\"Edit\" id=baten></a></td>
				<td><a href=\"./modul?do=del&kode=$modul[ID_MODUL]\"><img src=\"./icon/delete.png\" alt=\"Hapus\"></a></td>
				</tr>";
			}
		}
		else
			echo "<tr><td colspan='6' align='center'>Tidak ada data</td></tr>";
		
		?>
		</table>	
	</center>
<!-- Page Content Container -->
<?php
	include "footer.php";
?>