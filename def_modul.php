<?php
	include "header.php";
	if(isset($_POST['submit'])){
		//echo "<script>alert();</script>";		
		mysql_query("UPDATE MODUL_DEF SET AKTIF='0' WHERE AKTIF='1'");
		$lastmodul = "";
		for($i=0; $i<count($_POST['modul']); $i++){
			$modul = $_POST['modul'][$i];
			if($i>0)	$lastmodul = $_POST['modul'][$i-1];
			$device = $_POST['device'][$i];
			if($modul>0){
					
				$que = mysql_query("SELECT * FROM MODUL_DEF WHERE AKTIF='1' AND ID_MODUL='$modul'");
				$ada=mysql_num_rows($que);
				if($ada==0) 
					mysql_query("INSERT INTO MODUL_DEF VALUES('','$modul','1')");		
					
				$que = mysql_query("SELECT MAX(ID_MODUL_DEF) AS MDDEF FROM MODUL_DEF WHERE AKTIF='1'");
				while($data=mysql_fetch_assoc($que)){
					mysql_query("INSERT INTO MODUL_DEF_DET VALUES('','".$data[MDDEF]."','$device')");
				}
			}
		}
		echo "<script>document.location='./def_modul';</script>";
	}
?>
		<link href="jquery-ui-1.8.1.custom/css/custom-theme/jquery-ui-1.8.1.custom.css" rel="stylesheet" type="text/css" />
		<script src="jquery-ui-1.8.1.custom/js/jquery-1.4.2.min.js"></script>
		<script src="jquery-ui-1.8.1.custom/js/jquery-ui-1.8.1.custom.min.js"></script>
    <script type="text/javascript" src="js/menu.js"></script>
		
<script>
	$(document).ready(function(){				
		$("#setting").hide(0);	
	});

	function tampilkan(data){
		if(data==1)
			$("#setting").show();
		else
			$("#setting").hide(0);
	}
</script>
<center>
<font color="white"><h2> Pendefinisian Modul Pengendali</h2></font>
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
		<br>
		<table align="center" width="60%">
			<tr align=center bgcolor="#FF9933">
				<td>No.</td>
				<td>Modul Pengendali</td>
				<td>Piranti Elektronik</td>
			</tr>
			<?php
			$nomer=0;
			$que=mysql_query("SELECT MD.`ID_MODUL_DEF`,MDD.`ID_DET`,MD.`ID_MODUL`,M.`TOKEN`,D.`DEVICE_NAME`,D.`POWER` FROM MODUL_DEF MD LEFT JOIN MODUL_DEF_DET MDD ON MD.ID_MODUL_DEF=MDD.`ID_MODUL_DEF` LEFT JOIN DEVICE_LIST D ON MDD.`ID_DEVICE`=D.`ID_DEVICE` LEFT JOIN MODUL M ON MD.`ID_MODUL`=M.`ID_MODUL` WHERE MD.AKTIF='1'");
			if(mysql_num_rows($que)>0){
				while($data=mysql_fetch_assoc($que)){	
					$nomer=$nomer+1;
					$mod=$nomer%2;			
					if($mod==1)$color="#CCCCCC";
					else $color="#eaeced";
					echo "<tr align=center bgcolor=$color>
					<td>$nomer</td>				
					<td>Modul $data[ID_MODUL] - $data[TOKEN]</td>
					<td>$data[DEVICE_NAME]".(($data[POWER]>0)?" - ".$data[POWER]." Watt":"")."</td>";
				}
			}
			else
				echo "<tr><td colspan='3' align='center'>Tidak ada data</td></tr>";		
			?>
		</table>
		
		<br><input type="button" value="Setting" onclick="tampilkan(1);"><br><br>
	<div id="setting">
		<form method="post">	
		<table align="center" width="80%">
			<tr align=center bgcolor="#FF9933">
				<td width="10%">No.</td>
				<td width="20%">Nama Device</td>
				<td width="10%">Spesifikasi Daya</td>
				<td width="30%">Deskripsi</td>
				<td width="20">Terpasang di Modul Pengendali</td>
			</tr>
		<?php
		$nomer=0;
		$que=mysql_query("SELECT * FROM DEVICE_LIST WHERE DEL='0' ORDER BY ID_DEVICE ASC limit ".$nomer.",10");
		if(mysql_num_rows($que)>0){
			while($dev=mysql_fetch_array($que)){
				$nomer=$nomer+1;
				$mod=$nomer%2;
				if($mod==1)$color="#CCCCCC";
				else $color="#eaeced";
				echo "<tr align=center bgcolor=$color>
				<td>$nomer</td>				
				<td>$dev[DEVICE_NAME]</td>
				<td>";
				if($dev[POWER]>0)
					echo $dev[POWER]." WATT";
				else
					echo "Tidak Disetting";
				echo "</td>
				<td>$dev[EXPL]</td>
				<td>";
				echo "<input type='hidden' name='device[]' value='".$dev[ID_DEVICE]."'>";
				echo "<select name=\"modul[]\">
					<option value='0'>-</option>";
				$que2=mysql_query("SELECT * FROM MODUL WHERE DEL='0'");				
				while($modul=mysql_fetch_assoc($que2)){
					echo "<option value='".$modul[ID_MODUL]."'>MODUL ".$modul[ID_MODUL]." - ".$modul[TOKEN]."</option>";
				}
				echo "</select>
					</td>
				</tr>";
			}
		}
		else
			echo "<tr><td colspan='6' align='center'>Tidak ada data</td></tr>";		
		?>
			<tr>
				<td colspan="4"></td>
				<td align="center"><input type="submit" name="submit" value="Setting Baru"></td>
			</tr>
		</table>	
		</form>
		<input type="button" value="Cancel Setting" onclick="tampilkan(0);">
	</div>	
</center>
<?php
	include "footer.php";
?>