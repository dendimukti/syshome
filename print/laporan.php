
<?php 
	error_reporting(0);
	mysql_connect('localhost','root','');
	mysql_select_db('db_syshome');

	extract($_GET);
	echo "<center><h2>";
	if($type=="1"){
		echo "Laporan Penggunaan Daya Listrik Harian \"".date_format(date_create($tgl),"l, d F Y")."\"";
	}
	else if($type=="2"){
		echo "Laporan Penggunaan Daya Listrik Range Tanggal \"".date_format(date_create($tglmulai),"d M Y")." s/d ".date_format(date_create($tglsampai),"d M Y")."\"";
	}
	else if($type=="3"){
		echo "Laporan Penggunaan Daya Listrik Bulanan \"".date_format(date_create($tahun."-".$bulan."-01"),"F Y")."\"";
	}
	echo "</h2></center>";
	
function setHarian($m, $d, $y){
	$a=0;$hh=0;$mm=0;$ss=0;
	while($a<(12*24)){
		$harian[$a]=date("Y-m-d H:i:s", mktime($hh, $mm, $ss, $m, $d, $y));
		$mm+=5;
		if($mm==60){
			$hh++;
			$mm=0;
			if($hh==23)
				$harian[$a]=date("Y-m-d H:i:s", mktime(0, 0, 0, $m, $d+1, $y));
		}
		$a++;
	}
	return $harian;
}

function potong($data, $max){
//	$st=explode(".",$data);
//	if(strlen($st[1])>$max){
//		$st[1]=substr($st[1],0,$max);
//	}
//	return $st[0].".".$st[1];
	$number = number_format($data, $max, '.', '');
	return $number;
}
$koma=5;
function detail($tgl, $idmodul){
	global $whdaily, $kesharga, $keswh, $koma;	
			
	$sql="SELECT MDT.*, M.TOKEN, P.KWH_PRICE FROM MONITORING_DETAIL MDT LEFT JOIN MODUL_DEF MDF ON MDT.ID_MODUL_DEF=MDF.ID_MODUL_DEF LEFT JOIN MODUL M ON MDF.ID_MODUL=M.`ID_MODUL` LEFT JOIN PRICE P ON MDT.ID_PRICE=P.ID_PRICE WHERE DATE(MDT.DT)='".$tgl."' AND M.ID_MODUL='".$idmodul."' ORDER BY MDT.DT ASC";
	//echo $sql;
	$que=mysql_query($sql);
	if(mysql_num_rows($que)>0){
		$i=0;
		while($det=mysql_fetch_array($que)){
			$nomer[$i]=$i+1;
			$modul[$i]="Modul ".$det[ID_MODUL]." - ".$det[TOKEN];
			$hrg[$i]=$det[KWH_PRICE];
			$dt[$i]=$det[DT];
			$arus[$i]=$det[CURRENT];
			$idmoduldef=$det[ID_MODUL_DEF];
			$que2=mysql_query("SELECT D.DEVICE_NAME, D.POWER FROM MODUL_DEF_DET MDD LEFT JOIN DEVICE_LIST D ON MDD.ID_DEVICE=D.ID_DEVICE WHERE ID_MODUL_DEF='$idmoduldef'");
			$x=0;
			while($mdl=mysql_fetch_assoc($que2)){
				if($x>0) $devicedesc[$i].= ", ";
				$devicedesc[$i].=$mdl[DEVICE_NAME].(($mdl[POWER]>0)?" (".$mdl[POWER]." Watt)":"");
				$x++;
			}
			$i++;
		}
		$now = date('Y-m-d H:i:s');
		for($n=0;$n<$i-1;$n++){			
			$mod=$nomer[$n]%2;
			if($mod==1)$color="#CCCCCC";
			else $color="#eaeced";
				
			$datetime1 = new DateTime($dt[$n]);
			$datetime2 = new DateTime((!empty($dt[$n+1])?$dt[$n+1]:$now));
			$interval = $datetime1->diff($datetime2);
			$selisih = $interval->format("%Y:%M:%D:%H:%I:%S");
			$waktu=explode(":",$selisih);
			//$harga=(($waktu[0]/60)*$kwh[$n])." + ".(($waktu[1]/(60*60))*$kwh[$n])." + ".(($waktu[2]/(60*60*60))*$kwh[$n]); 
			$h=(($waktu[2]*24) + ($waktu[3]) + ($waktu[4]/(60)) + ($waktu[5]/(60*60))); 
			if($h>0.05) $arus[$n]=0; //3 menit
			$daya=($arus[$n] * 220);
			$wh = ($h * $daya);
			echo "<tr align=center bgcolor=$color>
			<td>$nomer[$n]</td>
			<td>$devicedesc[$n]</td>
			<td>".date("H:i:s",strtotime($dt[$n]))."</td>
			<td>".substr($selisih,9,8)."</td>
			<td>".potong($h,$koma)."</td>
			<td>$hrg[$n]</td>
			<td>$arus[$n]</td>
			<td>$daya</td>
			<td>".potong($wh,$koma)."</td>
			<td>".potong(($wh/1000),$koma)."</td>
			<td>Rp. ".potong((($wh/1000)*$hrg[$n]),$koma)."</td>
			</tr>";
			$harga=$wh*$hrg[$n];
			$totwh += $wh;
			$totharga += $harga;
		}
		$whdaily[$idmodul][]=$totwh;
	}
	else{
		$whdaily[$idmoduldef][]=0;
	}
	$kesharga += ($totharga/1000);
	$keswh += $totwh;	
	
	$return=array($totwh, ($totharga/1000));
	return $return;	
}

function detail2($tgl, $idmodul){
	global $whdaily, $kesharga, $keswh, $koma;
	$que=mysql_query("SELECT TOKEN, ID_MODUL FROM MODUL WHERE ID_MODUL='$idmodul'");
	while($mdl=mysql_fetch_assoc($que)){
		$moduldesc="MODUL ".$mdl[ID_MODUL]." - ".$mdl[TOKEN];
		$idmodul=$mdl[ID_MODUL];
	}
		
	$sql="SELECT MDT.*, M.TOKEN, P.KWH_PRICE FROM MONITORING_DETAIL MDT LEFT JOIN MODUL_DEF MDF ON MDT.ID_MODUL_DEF=MDF.ID_MODUL_DEF LEFT JOIN MODUL M ON MDF.ID_MODUL=M.`ID_MODUL` LEFT JOIN PRICE P ON MDT.ID_PRICE=P.ID_PRICE WHERE DATE(MDT.DT)='".$tgl."' AND M.ID_MODUL='".$idmodul."' ORDER BY MDT.DT ASC";
	//echo $sql;
	$que=mysql_query($sql);
	if(mysql_num_rows($que)>0){
		$i=0;
		while($det=mysql_fetch_array($que)){				
			$nomer[$i]=$i+1;
			$modul[$i]="Modul ".$det[ID_MODUL]." - ".$det[TOKEN];
			$hrg[$i]=$det[KWH_PRICE];
			$dt[$i]=$det[DT];
			$arus[$i]=$det[CURRENT];
			$i++;
		}
				
		$now = date('Y-m-d H:i:s');
		for($n=0;$n<$i-1;$n++){
			
			$que=mysql_query("SELECT D.DEVICE_NAME, D.POWER FROM MODUL_DEF MD LEFT JOIN MODUL_DEF_DET MDD ON MD.ID_MODUL_DEF=MDD.ID_MODUL_DEF LEFT JOIN DEVICE_LIST D ON MDD.ID_DEVICE=D.ID_DEVICE WHERE ID_MODUL='$idmodul' AND MD.AKTIF='1'");
			$x=0;
			while($mdl=mysql_fetch_assoc($que)){
				if($x>0) $devicedesc[$i].=", ";
				$devicedesc[$i].=$mdl[DEVICE_NAME].(($mdl[POWER]>0)?" (".$mdl[POWER]." Watt)":"");
				$x++;
			}
			
			$datetime1 = new DateTime($dt[$n]);
			$datetime2 = new DateTime((!empty($dt[$n+1])?$dt[$n+1]:$now));
			$interval = $datetime1->diff($datetime2);
			$selisih = $interval->format("%Y:%M:%D:%H:%I:%S");
			$waktu=explode(":",$selisih);
			//$harga=(($waktu[0]/60)*$kwh[$n])." + ".(($waktu[1]/(60*60))*$kwh[$n])." + ".(($waktu[2]/(60*60*60))*$kwh[$n]); 
			$h=(($waktu[2]*24) + ($waktu[3]) + ($waktu[4]/(60)) + ($waktu[5]/(60*60))); 
			if($h>0.05) $arus[$n]=0; //3 menit
			$daya=($arus[$n] * 220);
			$wh = ($h * $daya);
			$harga=$wh*$hrg[$n];
			$totwh += $wh;
			$totharga += $harga;
		}
		echo "
		<tr align=center bgcolor=#CCCCCC>
			<td>".date_format(date_create($tgl),"l, d F Y")."</td>
			<td>".potong($totwh,$koma)."</td>			
			<td>".potong(($totwh/1000),$koma)."</td>			
			<td>Rp. ".potong(($totharga/1000),$koma)."</td>
		</tr>";
		$whdaily[$idmodul][]=$totwh;
	}
	else{
		$whdaily[$idmodul][]=0;
		echo "<tr align=center bgcolor=#CCCCCC>
				<td>".date_format(date_create($tgl),"l, d F Y")."</td>
				<td>0</td>
				<td>0</td>
				<td>Rp. 0</td>
		</tr>";
	}
	$kesharga += ($totharga/1000);
	$keswh += $totwh;
	$return=array($totwh, ($totharga/1000));
	return $return;
}
?>
<?php
		
	$whdaily=array();
	
	if($type=="1"){
		if($idmodul=="x"){
			$que=mysql_query("SELECT ID_MODUL, TOKEN FROM MODUL WHERE DEL='0'");
			while($mdl=mysql_fetch_assoc($que)){
				$moduldesc="MODUL ".$mdl[ID_MODUL]." - ".$mdl[TOKEN];
				$idmodul=$mdl[ID_MODUL];
									
				echo '	<table align="center" width="80%">';	
				echo '<tr><td colspan="11" align=center bgcolor="#FF9933">'.$moduldesc.'</td></tr>
						<tr align=center bgcolor="#FF9933">
							<td width="5%">No.</td>
							<td width="30%">Piranti Elektronik</td>				
							<td width="7.5%">Waktu Pembacaan</td>
							<td width="7.5%">Lama</td>
							<td width="7.5%">Hour</td>
							<td width="7.5%">Harga per KWH</td>
							<td width="5%">Arus (Ampere)</td>
							<td width="5%">Daya (Watt)</td>
							<td width="7.5%">Energi (WH)</td>
							<td width="7.5%">Energi (KWH)</td>
							<td width="10%">Harga</td>
						</tr>';
				$tot=detail($tgl, $idmodul);
				echo "
				<tr align=center bgcolor=#FF9933>
					<td colspan='8'>Total Energi Listrik & Biaya Operasional</td>
					<td>".potong($tot[0],$koma)."</td>			
					<td>".potong(($tot[0]/1000),$koma)."</td>			
					<td>Rp. ".potong(($tot[1]),$koma)."</td>	
				</tr>";
				echo "</table><br><br>";
			}
		}
		else{			
			$que=mysql_query("SELECT ID_MODUL, TOKEN FROM MODUL WHERE ID_MODUL='$idmodul'");
			while($mdl=mysql_fetch_assoc($que)){
				$moduldesc="MODUL ".$mdl[ID_MODUL]." - ".$mdl[TOKEN];
				$idmodul=$mdl[ID_MODUL];									
				echo '	<table align="center" width="80%">';	
				echo '<tr><td colspan="11" align=center bgcolor="#FF9933">'.$moduldesc.'</td></tr>
						<tr align=center bgcolor="#FF9933">
							<td width="5%">No.</td>
							<td width="30%">Piranti Elektronik</td>				
							<td width="7.5%">Waktu Pembacaan</td>
							<td width="7.5%">Lama</td>
							<td width="7.5%">Hour</td>
							<td width="7.5%">Harga per KWH</td>
							<td width="5%">Arus (Ampere)</td>
							<td width="5%">Daya (Watt)</td>
							<td width="7.5%">Energi (WH)</td>
							<td width="7.5%">Energi (KWH)</td>
							<td width="10%">Harga</td>
						</tr>';					
				$tot=detail($tgl, $idmodul);
				echo "
				<tr align=center bgcolor=#FF9933>
					<td colspan='8'>Total Energi Listrik & Biaya Operasional</td>
					<td>".potong($tot[0],$koma)."</td>			
					<td>".potong(($tot[0]/1000),$koma)."</td>			
					<td>Rp. ".potong(($tot[1]),$koma)."</td>	
				</tr>";
				echo "</table><br><br>";
			}
		}
	}
	else if($type=="2")	{
		if(empty($tglmulai) || empty($tglsampai)){
			echo "<script>alert('Isi form dengan benar');
			document.location='./report';
			</script>";
		}
		else{							
			$diff = abs(strtotime($tglmulai) - strtotime($tglsampai));
			$days=floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24))+1;
			if($idmodul=="x"){
				$que=mysql_query("SELECT ID_MODUL, TOKEN FROM MODUL WHERE DEL='0'");
				while($mdl=mysql_fetch_assoc($que)){
					echo '<table align="center" width="50%">';	
					echo '<tr><td colspan="4" align=center bgcolor="#FF9933">Modul '.$mdl[ID_MODUL].' - '.$mdl[TOKEN].'</td></tr>
					<tr align=center bgcolor="#FF9933">
						<td width="35%">Tanggal</td>
						<td width="20%">WH</td>
						<td width="20%">KWH</td>
						<td width="25%">Harga</td>
					</tr>';			
					$a=0;
					$totwh=0;
					$totharga=0;
					while($a<$days){
						$tgllb = date('Y-m-d', strtotime($tglmulai . '+ '.$a.'days'));
						$a++;
						$tot=detail2($tgllb, $mdl[ID_MODUL]);
						$totwh+=$tot[0];
						$totharga+=$tot[1];
					}
					echo "<tr align='center' bgcolor='#FF9933'><td colspan='1'>TOTAL</td>
					<td>".potong($totwh,$koma)."</td>
					<td>".potong(($totwh/1000),$koma)."</td>
					<td>Rp. ".potong($totharga,$koma)."</td>
					</tr>";
					echo "</table><br><br>";		
				}
			}
			else{
				$que=mysql_query("SELECT ID_MODUL, TOKEN FROM MODUL WHERE ID_MODUL='$idmodul'");
				while($mdl=mysql_fetch_assoc($que)){
					echo '<table align="center" width="70%">';	
					echo '<tr><td colspan="4" align=center bgcolor="#FF9933">Modul '.$mdl[ID_MODUL].' - '.$mdl[TOKEN].'</td></tr>
					<tr align=center bgcolor="#FF9933">
						<td width="35%">Tanggal</td>
						<td width="20%">WH</td>
						<td width="20%">KWH</td>
						<td width="25%">Harga</td>
					</tr>';	
					$a=0;
					$totwh=0;
					$totharga=0;
					while($a<$days){
						$tgllb = date('Y-m-d', strtotime($tglmulai . '+ '.$a.'days'));
						$a++;
						$tot=detail2($tgllb, $idmodul);
						$totwh+=$tot[0];
						$totharga+=$tot[1];
					}
					echo "<tr align='center' bgcolor='#FF9933'><td colspan='1'>TOTAL</td>
					<td>".potong($totwh,$koma)."</td>
					<td>".potong(($totwh/1000),$koma)."</td>
					<td>Rp. ".potong($totharga,$koma)."</td>
					</tr>";
					echo "</table><br><br>";
				}
			}
		}
	}			
	else if($type=="3"){
		if($idmodul=="x"){
			$que=mysql_query("SELECT ID_MODUL, TOKEN FROM MODUL WHERE DEL='0'");
			while($mdl=mysql_fetch_assoc($que)){
				$a_date = $tahun."-".$bulan."-01";
				$l_date = date("Y-m-t", strtotime($a_date));
				$diff = abs(strtotime($a_date) - strtotime($l_date));
				$days=floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24))+1;
				$a=0;
				echo '<table align="center" width="70%">';	
				echo '<tr><td colspan="4" align=center bgcolor="#FF9933">Modul '.$mdl[ID_MODUL].' - '.$mdl[TOKEN].'</td></tr>
				<tr align=center bgcolor="#FF9933">
						<td width="35%">Tanggal</td>
						<td width="20%">WH</td>
						<td width="20%">KWH</td>
						<td width="25%">Harga</td>
				</tr>';	
				$totwh=0;
				$totharga=0;	
				while($a<$days){
					$tgllb = date('Y-m-d', strtotime($a_date . '+ '.$a.'days'));
					$a++;					
					$tot=detail2($tgllb, $mdl[ID_MODUL]);
					$totwh+=$tot[0];
					$totharga+=$tot[1];
				}
				echo "<tr align='center' bgcolor='#FF9933'><td colspan='1'>TOTAL</td>
				<td>".potong($totwh,$koma)."</td>
				<td>".potong(($totwh/1000),$koma)."</td>
				<td>Rp. ".potong($totharga,$koma)."</td>
				</tr>";
				echo "</table><br><br>";
			}
		}
		else{
			$que=mysql_query("SELECT ID_MODUL, TOKEN FROM MODUL WHERE ID_MODUL='$idmodul'");
			while($mdl=mysql_fetch_assoc($que)){
				$a_date = $tahun."-".$bulan."-01";
				$l_date = date("Y-m-t", strtotime($a_date));
				$diff = abs(strtotime($a_date) - strtotime($l_date));
				$days=floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24))+1;
				$a=0;
				echo '<table align="center" width="70%">';	
				echo '<tr><td colspan="4" align=center bgcolor="#FF9933">Modul '.$mdl[ID_MODUL].' - '.$mdl[TOKEN].'</td></tr>
				<tr align=center bgcolor="#FF9933">
						<td width="35%">Tanggal</td>
						<td width="20%">WH</td>
						<td width="20%">KWH</td>
						<td width="25%">Harga</td>
				</tr>';			
				$totwh=0;
				$totharga=0;
				while($a<$days){
					$tgllb = date('Y-m-d', strtotime($a_date . '+ '.$a.'days'));
					$a++;					
					$tot=detail2($tgllb, $idmodul);
					$totwh+=$tot[0];
					$totharga+=$tot[1];
				}
				echo "<tr align='center' bgcolor='#FF9933'><td colspan='1'>TOTAL</td>
				<td>".potong($totwh,$koma)."</td>
				<td>".potong(($totwh/1000),$koma)."</td>
				<td>Rp. ".potong($totharga,$koma)."</td>
				</tr>";
				echo "</table><br><br>";
			}
		}
	}
?>

<table align="center" width="30%">
	<tr align=center bgcolor="#FF9933">
		<td colspan="2">TOTAL KESELURUHAN</td>
	</tr>	
	<tr align=center bgcolor="#FF9933">
		<td width='50%'>ENERGI LISTRIK</td>
		<td>BIAYA OPERASIONAL</td>
	</tr>	
	<tr align=center bgcolor="#CCCCCC">
<?php
echo "	<td>".potong(($keswh/1000),$koma)." KWH</td>
		<td> Rp. ".potong($kesharga,$koma)."</td>";
?>
	</tr>
</table>

<script>
	timeout = setTimeout("window.print();",5000);
	//window.print();
</script>