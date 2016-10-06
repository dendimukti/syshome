<?php
	include "conn.php";
	function potong($data, $max){
		$number = number_format($data, $max, '.', '');
		return $number;
	}
?>
	<html>
	<center>
	<table align="center" border="0" cellspacing="2" cellpadding="5">
		<tr align="center" bgcolor="#FF9933">
			<td>No</td>
			<td>TGL</td>
			<td>MULAI</td>
			<td>SAMPAI</td>
			<td>MODUL DEF</td>
			<td>PIRANTI</td>
			<td>PEMBACAAN</td>
			<td>MAX</td>
			<td>MIN</td>
			<td>RATA2</td>
		</tr>
<?php	
	$sql="SELECT DATE(DT) AS TGL, MIN(TIME(DT)) AS MULAI, MAX(TIME(DT)) AS SAMPAI, ID_MODUL_DEF, COUNT(ID_DETAIL) AS PEMBACAAN, MAX(CURRENT) AS MAKS, MIN(CURRENT) AS MINIM, AVG(CURRENT) AS RATA FROM MONITORING_DETAIL WHERE (DT between '2016-09-20 00:00:00' AND '2016-09-26 00:00:00') GROUP BY TGL, ID_MODUL_DEF ORDER BY DT ASC";
	$que=mysql_query($sql);
	$n=0;
	while($data=mysql_fetch_assoc($que)){
		$n++;		
		$d=explode("-",$data['TGL']);
		$tgl=date("l, d M Y", mktime(0, 0, 0, $d[1], $d[2], $d[0]));
		if($d[2]%2==1) $warna="#CCCCCC";
		else $warna="#f5f5f1";
		echo "
		<tr align=center bgcolor='$warna'>
			<td>$n</td>
			<td>$tgl</td>
			<td>$data[MULAI]</td>
			<td>$data[SAMPAI]</td>
			<td>";
			
			$que2=mysql_query("SELECT M.TOKEN FROM MODUL_DEF MD LEFT JOIN MODUL M ON MD.ID_MODUL=M.ID_MODUL WHERE MD.ID_MODUL_DEF='".$data['ID_MODUL_DEF']."'");
			
			while($mdl=mysql_fetch_assoc($que2)){
				$token=$mdl['TOKEN'];
			}
			echo $data['ID_MODUL_DEF']." - ".$token;
			echo "</td>
			<td>";
			
			$que3=mysql_query("SELECT D.DEVICE_NAME, D.POWER FROM MODUL_DEF_DET MDD LEFT JOIN DEVICE_LIST D ON MDD.ID_DEVICE=D.ID_DEVICE WHERE MDD.ID_MODUL_DEF='".$data['ID_MODUL_DEF']."'");
			$x=0;
			$devicedesc="";
			while($mdl=mysql_fetch_assoc($que3)){
				if($x>0) $devicedesc.=", ";
				$devicedesc .=$mdl['DEVICE_NAME'].(($mdl['POWER']>0)?" (".$mdl['POWER']." Watt)":"");
				$x++;
			}
			echo $devicedesc;
			
			echo "</td>
			<td>".$data['PEMBACAAN']." kali</td>
			<td>".potong(($data['MAKS']*220),2)." Watt</td>
			<td>".potong(($data['MINIM']*220),2)." Watt</td>
			<td>".potong(($data['RATA']*220),2)." Watt</td>
		</tr>";
		
	}
?>
</table>
</center>
</html>