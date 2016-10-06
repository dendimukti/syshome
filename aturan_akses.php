<?php
	include "header.php";
	extract($_REQUEST);
	if(isset($inspriv)){
		if(($tipe==1 || $tipe==2) && $modul!=0){
			$time1 = $jam1.":".$menit1.":00";
			$time2 = $jam2.":".$menit2.":00";
			$timea = strtotime($time1);
			$timeb = strtotime($time2);
//			echo "timea=".$timea."<br>";
//			echo "timeb=".$timeb."<br>";
			if($timea == $timeb){
				echo "<script>alert('Waktu mulai dan batas waktu pengaturan tidak boleh sama');</script>";
			}				
			else if($timea < $timeb){
				mysql_query("INSERT INTO PRIVILEDGE VALUES('','$modul','$tipe','$time1','$time2')");
				echo "<script>alert('data pengaturan baru telah terinput');</script>";				
			}	
			else if($timea > $timeb){
				mysql_query("INSERT INTO PRIVILEDGE VALUES('','$modul','$tipe','$time1','00:00:00')");
				if($time2 != "00:00:00")
					mysql_query("INSERT INTO PRIVILEDGE VALUES('','$modul','$tipe','00:00:00','$time2')");
				echo "<script>alert('data pengaturan baru telah terinput');</script>";	
			}
		}else{
			echo "<script>alert('Isi form dengan benar');</script>";
		}
		
		//echo "<script>document.location='./aturan_akses';</script>";
	}
	else if($act=="del" && !empty($id)){
		mysql_query("DELETE FROM PRIVILEDGE WHERE ID_AKSES='$id'");
		echo "<script>document.location='./aturan_akses';</script>";
	}
?>

<center>
<font color="white">
	<h2> Pengaturan Akses</h2>
	<br>
<form method="post">
	<table>
		<tr>
			<td>Modul Pengendali</td>
			<td>:</td>
			<td>
				<select name="modul">
					<option value='0'>- Pilih -</option>
					<?php
				$que2=mysql_query("SELECT * FROM MODUL WHERE DEL='0'");					
				while($modul=mysql_fetch_assoc($que2)){
					echo "<option value='".$modul[ID_MODUL]."'>MODUL ".$modul[ID_MODUL]." - ".$modul[TOKEN]."</option>";
				}
				?>
				</select>
			</td>
		</tr>
		<tr>
			<td>Tipe</td>
			<td>:</td>
			<td>
				<select name="tipe">
					<option value=''>-Pilih-</option>
					<option value='1'>Aktifkan Harian</option>
					<option value='2'>Nonaktifkan Harian</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>Mulai</td>
			<td>:</td>
			<td>
				<select name="jam1">
				<?php
				for($i=0;$i<24;$i++){
					echo "<option value='".((strlen($i)==1)?"0".$i:$i)."'>".((strlen($i)==1)?"0".$i:$i)."</option>";
				}
				?>
				</select> : 
				<select name="menit1">
				<?php
				for($i=0;$i<60;$i++){
					echo "<option value='".((strlen($i)==1)?"0".$i:$i)."'>".((strlen($i)==1)?"0".$i:$i)."</option>";
				}
				?>
				</select>
				<!--
				<input type="text" name="time1" id="timepicker1"/>
				<input type="text" name="time1" id="time1"/>
				-->
			</td>
		</tr>
		<tr>
			<td>Sampai</td>
			<td>:</td>
			<td>
				<select name="jam2">
				<?php
				for($i=0;$i<24;$i++){
					echo "<option value='".((strlen($i)==1)?"0".$i:$i)."'>".((strlen($i)==1)?"0".$i:$i)."</option>";
				}
				?>
				</select> : 
				<select name="menit2">
				<?php
				for($i=0;$i<60;$i++){
					echo "<option value='".((strlen($i)==1)?"0".$i:$i)."'>".((strlen($i)==1)?"0".$i:$i)."</option>";
				}
				?>
				</select>
				<!--
				<input type="text" name="time2" id="timepicker2"/>
				-->
			</td>
		</tr>
		<tr>
			<td colspan="3"><input type="submit" name="inspriv"></td>
		</tr>
	</table>
</form>
</font>
	<table align="center" width="80%">
		<tr align=center bgcolor="#FF9933">
			<td width="8%">No</td>
			<td width="25%">Modul</td>
			<td width="20%">Tipe</td>
			<td width="20%">Waktu Mulai</td>
			<td width="20%">Waktu Akhir</td>
			<td>Aksi</td>
		</tr>
		<?php
			$que=mysql_query("SELECT P.*, M.TOKEN FROM PRIVILEDGE P LEFT JOIN MODUL M ON P.ID_MODUL=M.ID_MODUL");
			$i=0;
			while($data=mysql_fetch_assoc($que)){
				$i++;
				$mod=$i%2;
				if($mod==1)$color="#CCCCCC";
				else $color="#eaeced";
				echo "<tr align=center bgcolor=$color>
					<td>$i</td>
					<td>Modul ".$data[ID_MODUL]." - ".$data[TOKEN]."</td>
					<td>";
					if($data[TYPE]==1)	echo "Aktifan Harian";
					else if($data[TYPE]==2)	echo "Nonaktifan Harian";
					echo "</td>
					<td>";
						echo $data[HARIAN_START];
					echo "</td>
					<td>";
						echo $data[HARIAN_FINISH];
					echo "</td>
					<td>";
						echo "<a href='./aturan_akses?act=del&id=".$data[ID_AKSES]."'><img src='icon/delete.png'></a>";
					echo "</td>
				</tr>";
			}
		?>
	</table>
</center>

<?php
	include "footer.php";
?>