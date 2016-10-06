<?php
	include "conn.php";
?>
	<html>
	<center>
	<table align="center" border="1">
		<tr align="center">
			<td>No</td>
			<td>ID MODUL DEF</td>
			<td>CURRENT</td>
			<td>DT</td>
			<td>DT NEW</td>
		</tr>
<?php	
	//$sql="SELECT * FROM MONITORING_DETAIL WHERE DT between '2016-09-22 12:35:09' AND '2016-09-22 17:50:09' ORDER BY DT ASC";
	$sql="SELECT *, DATE(DT) AS D, TIME(DT) AS T FROM MONITORING_DETAIL WHERE DT between '2016-09-24 00:00:22' AND '2016-09-24 23:00:38' ORDER BY DT ASC";
	//$sql="SELECT *, DATE(DT) AS D, TIME(DT) AS T FROM MONITORING_DETAIL WHERE DT between '2016-09-22 13:00:00' AND '2016-09-22 14:15:09' ORDER BY DT ASC";
	$que=mysql_query($sql);
	$n=0;
	while($data=mysql_fetch_assoc($que)){
		$n++;
		//if($n==1) {
		if(false){
			$sql2="SELECT * FROM MONITORING_DETAIL WHERE ID_DETAIL='".($data['ID_DETAIL'] - 1)."'";
			$que2=mysql_query($sql2);
			$data2=mysql_fetch_assoc($que2);
			echo "
			<tr align=center>
				<td>0</td>
				<td>$data2[ID_MODUL_DEF]</td>
				<td>$data2[CURRENT]</td>
				<td>$data2[DT]</td>
				<td></td>
			</tr>";
		}
		
		$d=explode("-",$data['D']);
		$t=explode(":",$data['T']);
		//2016-09-22 15:09:35
		$tgl=date("Y-m-d H:i:s", mktime($t[0], $t[1], $t[2], $d[1], $d[2], $d[0]));
		
		echo "
		<tr align=center>
			<td>$n</td>
			<td>$data[ID_MODUL_DEF]</td>
			<td>$data[CURRENT]</td>
			<td>$data[DT]</td>
			<td>$tgl</td>
		</tr>";
		
	//	mysql_query("UPDATE MONITORING_DETAIL SET DT='$tgl' WHERE ID_DETAIL='$data[ID_DETAIL]'");
	}
?>
</table>
</center>
</html>