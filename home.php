<?php
	include "header.php";
	
?>
<font color="white">
<h2> Notifikasi Terbaru</h2>
</font>
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
		<table align="center" width="100%">
			<tr align=center bgcolor="#FF9933">
				<td width="10%">No.</td>
				<td width="30">Modul</td>
				<td width="10%">DT Alert</td>
				<td width="50%">Deskripsi</td>
			</tr>
		<?php
		$que=mysql_query("SELECT A.*, M.TOKEN FROM ALERT A LEFT JOIN MODUL_DEF MD ON A.ID_MODUL_DEF=MD.ID_MODUL_DEF LEFT JOIN MODUL M ON M.ID_MODUL=MD.ID_MODUL WHERE A.SEEN='0' ORDER BY A.DT_ALERT DESC");
		if(mysql_num_rows($que)>0){
			while($alert=mysql_fetch_array($que)){
				$nomer=$nomer+1;
				$mod=$nomer%2;
				if($mod==1)$color="#CCCCCC";
				else $color="#eaeced";
				
				echo "<tr align=center bgcolor=$color>
				<td>$nomer</td>
				<td>Modul $alert[ID_MODUL] - $alert[IP_ADDR]</td>
				<td>$alert[DT_ALERT]</td>
				<td>$alert[EXPL]</td>
				</tr>";				
			}
		}
		else
			echo "<tr><td colspan='6' align='center'>Tidak ada Notifikasi</td></tr>";
		
		?>
		</table>	

<?php
	include "footer.php";
?>