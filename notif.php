<?php
	include "header.php";
?>
<center>
<font color="white">
<h2> Notifikasi Terbaru</h2>
</font>
<br>
		<table align="center" width="60%">
			<tr align=center bgcolor="#FF9933">
				<td width="10%">No.</td>
				<td width="30">Modul</td>
				<td width="20%">DT Alert</td>
				<td width="40%">Deskripsi</td>
			</tr>
		<?php
		$que=mysql_query("SELECT A.*, M.ID_MODUL, M.TOKEN FROM ALERT A LEFT JOIN MODUL M ON M.ID_MODUL=A.ID_MODUL WHERE A.SEEN='0' OR A.SEEN='2' ORDER BY A.DT_ALERT DESC ");
		if(mysql_num_rows($que)>0){
			while($alert=mysql_fetch_array($que)){
				$nomer=$nomer+1;
				$mod=$nomer%2;
				if($mod==1)$color="#CCCCCC";
				else $color="#eaeced";
				echo "<tr align=center bgcolor=$color>
				<td>$nomer</td>
				<td>Modul $alert[ID_MODUL] - $alert[TOKEN]</td>
				<td>$alert[DT_ALERT]</td>
				<td>$alert[EXPL]</td>
				</tr>";				
			}
		}
		else
			echo "<tr><td colspan='6' align='center'>Tidak ada Notifikasi</td></tr>";
		
		mysql_query("UPDATE ALERT SET SEEN=1 WHERE SEEN=0");
		?>
		</table>
	</center>	
<?php	
	include "footer.php";
?>