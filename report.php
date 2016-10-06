<?php
	include "header.php";
	$chart=true;
	extract($_GET);
	
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

$koma=2;
function potong($data, $max){
//	$st=explode(".",$data);
//	if(strlen($st[1])>$max){
//		$st[1]=substr($st[1],0,$max);
//	}
//	return $st[0].".".$st[1];
	$number = number_format($data, $max, '.', '');
	return $number;
}
?>
		<link href="jquery-ui-1.8.1.custom/css/custom-theme/jquery-ui-1.8.1.custom.css" rel="stylesheet" type="text/css" />
		<script src="jquery-ui-1.8.1.custom/js/jquery-1.4.2.min.js"></script>
		<script src="jquery-ui-1.8.1.custom/js/jquery-ui-1.8.1.custom.min.js"></script>
		<script type="text/javascript" src="chart/js/fusioncharts.js"></script>
		<script type="text/javascript" src="chart/js/themes/fusioncharts.theme.fint.js?cacheBust=56"></script>
		<script type="text/javascript" src="js/menu.js"></script>
<script>
$(document).ready(function(){
	$('.tgl').datepicker({showAnim:'blind',dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,yearRange:'-1:+1'});
				
	$("#table1").hide(0);
	$("#table2").hide(0);
	$("#table3").hide(0);
	$("#table4").hide(0);
	<?php
		if($type=="1"){
			echo '$("#table1").show();';
			echo '$("#table4").show();';	
		}
		else if($type=="2"){
			echo '$("#table2").show();';
			echo '$("#table4").show();';
		}
		else if($type=="3"){
			echo '$("#table3").show();';
			echo '$("#table4").show();';
		}
	?>			
});
			
	function pilihan(data){
				/*
				alert(data);
				//$(obj).parent().parent().html("");
				var pilih = parseInt(data);
				//alert(data);
					$.ajax({
						url		:	"includes/pilihan.php?type=" + pilih,
						type	:	"GET",
						success	:	function(text){
							$("#table").html(text);							
						}
					});
					*/
		if(data==1){
			$("#table1").show();
			$("#table2").hide();
			$("#table3").hide();
			$("#table4").show();
		}
		else if(data==2){
			$("#table1").hide();
			$("#table2").show();
			$("#table3").hide();
			$("#table4").show();
		}
		else if(data==3){
			$("#table1").hide();
			$("#table2").hide();
			$("#table3").show();
			$("#table4").show();
		}
		else{
			$("#table1").hide();
			$("#table2").hide();
			$("#table3").hide();
			$("#table4").hide();
		}
	}

</script>

<center>
<font color="white">
<h2> Laporan Penggunaan Listrik </h2>
<br>
<form method="get">
	<table width="25%">
		<tr>
			<td>Pilih Modul</td>
			<td align="right">
				<select name="idmodul">
					<option value="x">Tampilkan Semua</option>
<?php
				$que2=mysql_query("SELECT * FROM MODUL WHERE DEL='0'");
				while($data=mysql_fetch_assoc($que2)){
					echo "<option value='".$data[ID_MODUL]."' ";
					if($data[ID_MODUL]==$idmodul){
						echo "selected";
					}
					echo ">MODUL ".$data[ID_MODUL]." - ".$data[TOKEN]."</option>";					
				}
?>
				</select>
			</td>
		</tr>
		<tr>
			<td>Pilih Tipe Laporan</td>
			<td align="right">
				<select name="type" onchange="pilihan(this.value);">
					<option value="">Pilih</option>
					<option value="1" <?php if($type=="1") echo "selected"?>>Harian</option>
					<option value="2" <?php if($type=="2") echo "selected"?>>Range Tanggal</option>
					<option value="3" <?php if($type=="3") echo "selected"?>>Bulanan</option>
				</select>
			</td>
		</tr>
	</table>
	
	<div id="table1">
		<table width="25%" border="1">
			<tr>
				<td width="50%">Tanggal</td>
				<td align="right"><input type="text" name="tgl" class="tgl" readonly="" value="<?php if($type=="1") echo $tgl;?>"></td>
			</tr>
		</table>
	</div>
	<div id="table2">
		<table width="25%" border="1">
			<tr>
				<td width="50%">Tanggal Mulai</td>
				<td align="right"><input type="text" name="tglmulai" class="tgl" readonly="" value="<?php if($type=="2") echo $tglmulai;?>"></td>
			</tr>
			<tr>
				<td>Tanggal Sampai</td>
				<td align="right"><input type="text" name="tglsampai" class="tgl" readonly="" value="<?php if($type=="2") echo $tglsampai;?>"></td>
			</tr>
		</table>
	</div>
	<div id="table3">
		<table width="25%" border="1">
			<tr>
				<td width="50%">Bulan</td>
				<td align="right">
					<select name="bulan">
						<option value="01" <?php if($type=="3" && $bulan=="01") echo "selected";
												 else if($type!="3") echo (date('m')=="01")?"selected":"";?>>Januari</option>
						<option value="02" <?php if($type=="3" && $bulan=="02") echo "selected";
												 else if($type!="3") echo (date('m')=="02")?"selected":"";?>>Pebruari</option>
						<option value="03" <?php if($type=="3" && $bulan=="03") echo "selected";
												 else if($type!="3") echo (date('m')=="03")?"selected":"";?>>Maret</option>
						<option value="04" <?php if($type=="3" && $bulan=="04") echo "selected";
												 else if($type!="3") echo (date('m')=="04")?"selected":"";?>>April</option>
						<option value="05" <?php if($type=="3" && $bulan=="05") echo "selected";
												 else if($type!="3") echo (date('m')=="05")?"selected":"";?>>Mei</option>
						<option value="06" <?php if($type=="3" && $bulan=="06") echo "selected";
												 else if($type!="3") echo (date('m')=="06")?"selected":"";?>>Juni</option>
						<option value="07" <?php if($type=="3" && $bulan=="07") echo "selected";
												 else if($type!="3") echo (date('m')=="07")?"selected":"";?>>Juli</option>
						<option value="08" <?php if($type=="3" && $bulan=="08") echo "selected";
												 else if($type!="3") echo (date('m')=="08")?"selected":"";?>>Agustus</option>
						<option value="09" <?php if($type=="3" && $bulan=="09") echo "selected";
												 else if($type!="3") echo (date('m')=="09")?"selected":"";?>>September</option>
						<option value="10" <?php if($type=="3" && $bulan=="10") echo "selected";
												 else if($type!="3") echo (date('m')=="10")?"selected":"";?>>Oktober</option>
						<option value="11" <?php if($type=="3" && $bulan=="11") echo "selected";
												 else if($type!="3") echo (date('m')=="11")?"selected":"";?>>November</option>
						<option value="12" <?php if($type=="3" && $bulan=="12") echo "selected";
												 else if($type!="3") echo (date('m')=="12")?"selected":"";?>>Desember</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>Tahun</td>
				<td align="right">
					<select name="tahun">
						<?php
						for($i=(date('Y')-5);$i<=(date('Y')+5);$i++){
							if($type=="3")
								echo '<option value="'.$i.'" '.(($i==$tahun)?"selected":"").'>'.$i.'</option>';
							else
								echo '<option value="'.$i.'" '.(($i==date('Y'))?"selected":"").'>'.$i.'</option>';	
						}
						?>
					</select>
				</td>
			</tr>
		</table>
	</div>
	<div id="table4">
		<table width="25%">
			<tr>
				<td align="right" colspan="2"><input type="submit" name="submitreport"></td>
			</tr>
		</table>
	</div>
	<br><br>
	<div id="chart-container"></div>
	<br>
	<?php
if(isset($submitreport)){
	$kesharga=0;
	$keswh=0;
	echo "<a href='./print/grafik.php?idmodul=$idmodul&type=$type&tgl=$tgl&tglmulai=$tglmulai&tglsampai=$tglsampai&bulan=$bulan&tahun=$tahun' target='_blank'><font color=white>Print Grafik</font></a> | <a href='./print/laporan.php?idmodul=$idmodul&type=$type&tgl=$tgl&tglmulai=$tglmulai&tglsampai=$tglsampai&bulan=$bulan&tahun=$tahun' target='_blank'><font color=white>Print Laporan</font></a>";
}	
	?>
	<br><br>
</form>
</font>
<?php
function detail($tgl, $idmodul){
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
				if($x<0) echo ", ";
				$devicedesc=$mdl[DEVICE_NAME]." ( ".$mdl[POWER]." Watt )";
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
			<td>
<b>[ <a href='./print/grafik.php?idmodul=$idmodul&type=1&tgl=$tgl' target='_blank'><font color=blue>Detail Grafik</font></a> ] | [ <a href='./print/laporan.php?idmodul=$idmodul&type=1&tgl=$tgl' target='_blank'><font color=blue>Detail Laporan</font></a> ]</b>
			</td>
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
				<td></td>
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
extract($_GET);
if(isset($submitreport)){		
	$whdaily=array();
	$queadd="";
	if($type=="1"){
		if(empty($tgl)){
			echo "<script>alert('Isi form dengan benar');
			document.location='./report';
			</script>";
		}
		else{
			if($idmodul=="x"){
				$que=mysql_query("SELECT ID_MODUL, TOKEN FROM MODUL WHERE DEL='0'");
				while($mdl=mysql_fetch_assoc($que)){
					echo '<table align="center" width="70%">';	
					echo '<tr><td colspan="5" align=center bgcolor="#FF9933">Modul '.$mdl[ID_MODUL].' - '.$mdl[TOKEN].'</td></tr>
					<tr align=center bgcolor="#FF9933">
						<td width="30%">Tanggal</td>
						<td width="35%">Print</td>
						<td width="10%">WH</td>
						<td width="10%">KWH</td>
						<td width="15%">Harga</td>
					</tr>';
					$tot=detail($tgl, $mdl[ID_MODUL]);
					echo "<tr align='center' bgcolor='#FF9933'><td colspan='2'>TOTAL</td>
					<td>".potong($tot[0],$koma)."</td>
					<td>".potong(($tot[0]/1000),$koma)."</td>
					<td>Rp. ".potong($tot[1],$koma)."</td>
					</tr>";
					echo "</table><br><br>";
				}
			}
			else{
				$que=mysql_query("SELECT ID_MODUL, TOKEN FROM MODUL WHERE ID_MODUL='$idmodul'");
				while($mdl=mysql_fetch_assoc($que)){
					echo '<table align="center" width="70%">';	
					echo '<tr><td colspan="5" align=center bgcolor="#FF9933">Modul '.$mdl[ID_MODUL].' - '.$mdl[TOKEN].'</td></tr>
					<tr align=center bgcolor="#FF9933">
						<td width="30%">Tanggal</td>
						<td width="35%">Print</td>
						<td width="10%">WH</td>
						<td width="10%">KWH</td>
						<td width="15%">Harga</td>
					</tr>';				
					$tot=detail($tgl, $idmodul);
					echo "<tr align='center' bgcolor='#FF9933'><td colspan='2'>TOTAL</td>
					<td>".potong($tot[0],$koma)."</td>
					<td>".potong(($tot[0]/1000),$koma)."</td>
					<td>Rp. ".potong($tot[1],$koma)."</td>
					</tr>";
					echo "</table><br><br>";
				}
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
					echo '<table align="center" width="70%">';	
					echo '<tr><td colspan="5" align=center bgcolor="#FF9933">Modul '.$mdl[ID_MODUL].' - '.$mdl[TOKEN].'</td></tr>
					<tr align=center bgcolor="#FF9933">
						<td width="30%">Tanggal</td>
						<td width="35%">Print</td>
						<td width="10%">WH</td>
						<td width="10%">KWH</td>
						<td width="15%">Harga</td>
					</tr>';		
					$a=0;
					$totwh=0;
					$totharga=0;
					while($a<$days){
						$tgllb = date('Y-m-d', strtotime($tglmulai . '+ '.$a.'days'));
						$a++;
						$tot=detail($tgllb, $mdl[ID_MODUL]);
						$totwh+=$tot[0];
						$totharga+=$tot[1];
					}
					echo "<tr align='center' bgcolor='#FF9933'><td colspan='2'>TOTAL</td>
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
					echo '<tr><td colspan="5" align=center bgcolor="#FF9933">Modul '.$mdl[ID_MODUL].' - '.$mdl[TOKEN].'</td></tr>
					<tr align=center bgcolor="#FF9933">
						<td width="30%">Tanggal</td>
						<td width="35%">Print</td>
						<td width="10%">WH</td>
						<td width="10%">KWH</td>
						<td width="15%">Harga</td>
					</tr>';
					$a=0;
					$totwh=0;
					$totharga=0;
					while($a<$days){
						$tgllb = date('Y-m-d', strtotime($tglmulai . '+ '.$a.'days'));
						$a++;
						$tot=detail($tgllb, $idmodul);
						$totwh+=$tot[0];
						$totharga+=$tot[1];
					}
					echo "<tr align='center' bgcolor='#FF9933'><td colspan='2'>TOTAL</td>
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
				echo '<tr><td colspan="5" align=center bgcolor="#FF9933">Modul '.$mdl[ID_MODUL].' - '.$mdl[TOKEN].'</td></tr>
				<tr align=center bgcolor="#FF9933">
					<td width="30%">Tanggal</td>
					<td width="35%">Print</td>
					<td width="10%">WH</td>
					<td width="10%">KWH</td>
					<td width="15%">Harga</td>
				</tr>';
				$totwh=0;
				$totharga=0;	
				while($a<$days){
					$tgllb = date('Y-m-d', strtotime($a_date . '+ '.$a.'days'));
					$a++;					
					$tot=detail($tgllb, $mdl[ID_MODUL]);
					$totwh+=$tot[0];
					$totharga+=$tot[1];
				}
				echo "<tr align='center' bgcolor='#FF9933'><td colspan='2'>TOTAL</td>
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
				echo '<tr><td colspan="5" align=center bgcolor="#FF9933">Modul '.$mdl[ID_MODUL].' - '.$mdl[TOKEN].'</td></tr>
				<tr align=center bgcolor="#FF9933">
					<td width="30%">Tanggal</td>
					<td width="35%">Print</td>
					<td width="10%">WH</td>
					<td width="10%">KWH</td>
					<td width="15%">Harga</td>
				</tr>';	
				$totwh=0;
				$totharga=0;
				while($a<$days){
					$tgllb = date('Y-m-d', strtotime($a_date . '+ '.$a.'days'));
					$a++;					
					$tot=detail($tgllb, $idmodul);
					$totwh+=$tot[0];
					$totharga+=$tot[1];
				}
				echo "<tr align='center' bgcolor='#FF9933'><td colspan='2'>TOTAL</td>
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
FusionCharts.ready(function(){
    var fusioncharts = new FusionCharts({
    type: 'msline',
    renderAt: 'chart-container',
    width: '1300',
    height: '600',
    dataFormat: 'json',
    dataSource: {
        "chart": {
            "caption": "Data Penggunaan Listrik <?php 
			if($type=="1") echo"Harian";
			else if($type=="2") echo "Range Tanggal";
			else if($type=="3") echo "Bulanan";
			?>",
            "subCaption": "<?php 
			if($type=="1") echo date_format(date_create($tgl),"l, d M Y");
			else if($type=="2") echo date_format(date_create($tglmulai),"l, d M Y")." -> ".date_format(date_create($tglsampai),"l, d M Y");
			else if($type=="3") echo date_format(date_create($tahun."-".$bulan."-01"),"M Y");
			?>",
            "captionFontSize": "14",
            "subcaptionFontSize": "14",
            "subcaptionFontBold": "0",
            "paletteColors": "#0075c2,#1aaf5d",
            "bgcolor": "#ffffff",
            "showBorder": "0",
            "showShadow": "0",
            "showCanvasBorder": "0",
            "usePlotGradientColor": "0",
            "legendBorderAlpha": "0",
            "legendShadow": "0",
            "showAxisLines": "0",
            "showAlternateHGridColor": "0",
            "divlineThickness": "1",
            "divLineIsDashed": "1",
            "divLineDashLen": "1",
            "divLineGapLen": "1",
            "xAxisName": "<?php 
			if($type=="1") echo "Pukul";
			else if($type=="2") echo "Tanggal";
			else if($type=="3") echo date_format(date_create($tahun."-".$bulan."-01"),"M Y");
			?>",
            "yAxisName": "<?php 
			if($type=="1") echo "Watt";
			else echo "Watt Hour";
			?>",
            "showValues": "0",
            "anchorRadius": "0"
            <?php
            if($type==1) echo ',
            "yAxisMaxValue": "550"';
            ?>
        },
        "categories": [{
            "category": [
			<?php 
			if($type=="1"){
				while($a<(12*24)){
					if($a>0) echo ",";
					if($a==0 || ($a%12)==0)
						echo '{"label": "'.($a/12).'"}';
					else
						echo '{"label": ""}';
					$a++;
				}
			}
			else if($type=="2"){				
				$diff = abs(strtotime($tglmulai) - strtotime($tglsampai));				
//				$years = floor($diff / (365*60*60*24));
//				$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
				$days=floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24))+1;				
				$a=0;
				while($a<$days){
					if($a>0) echo ",";
					$tgllb = date('d/m', strtotime($tglmulai . '+ '.$a.'days'));
					echo '{"label": "'.($tgllb).'"}';
					$a++;
				}				
			}
			else if($type=="3"){
				$a_date = $tahun."-".$bulan."-01";
				$l_date = date("Y-m-t", strtotime($a_date));
				$diff = abs(strtotime($a_date) - strtotime($l_date));
				$days=floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24))+1;
				$a=0;
				while($a<$days){
					if($a>0) echo ",";
					echo '{"label": "'.($a+1).'"}';
					$a++;
				}		
			}
			?>
                
            ]
        }],
        "dataset": [
        <?php
        if($type=="1"){
        	if($idmodul!="x")
        		$quemdl=mysql_query("SELECT ID_MODUL, TOKEN FROM MODUL WHERE ID_MODUL='$idmodul' AND DEL='0'");
        	else
        		$quemdl=mysql_query("SELECT ID_MODUL, TOKEN FROM MODUL WHERE DEL='0'");
        	$a=0;
        	while($datamodul=mysql_fetch_assoc($quemdl)){		
        		if($a>0) echo ",";      		
            	$idmodul=$datamodul[ID_MODUL];
        		$que=mysql_query("SELECT D.DEVICE_NAME, D.POWER FROM MODUL_DEF MD LEFT JOIN MODUL_DEF_DET MDD ON MD.ID_MODUL_DEF=MDD.ID_MODUL_DEF LEFT JOIN DEVICE_LIST D ON MDD.ID_DEVICE=D.ID_DEVICE WHERE ID_MODUL='$idmodul' AND MD.AKTIF='1'");
				$x=0;
				while($mdl=mysql_fetch_assoc($que)){
					if($x<0) echo ", ";
					$devicedesc=$mdl[DEVICE_NAME];
					$x++;
				}
	echo '{
            "seriesname": "Modul '.$datamodul[ID_MODUL].' - '.$datamodul[TOKEN].'",
            "data": [	';
            	$harian=explode("-",$tgl);
				$harian=setHarian($harian[1], $harian[2], $harian[0]);
				$echo="";    	
            	for($i=0;$i<count($harian)-1;$i++){    
					if($i>0) echo ",";
					$data=mysql_fetch_array(mysql_query("SELECT AVG(CURRENT) AS ARUS FROM MONITORING_DETAIL MDT LEFT JOIN MODUL_DEF MD ON MDT.ID_MODUL_DEF=MD.ID_MODUL_DEF WHERE MD.ID_MODUL='$idmodul' AND (MDT.DT>='".$harian[$i]."' AND MDT.DT<'".$harian[$i+1]."')"));
//					$arr[]="SELECT AVG(CURRENT) AS ARUS FROM MONITORING_DETAIL WHERE ID_MODUL_DEF='$idmoduldef' AND (DT>='".$harian[$i]."' AND DT<'".$harian[$i+1]."')";
			        if(!empty($data[ARUS]))
						echo '{"value": "'.($data[ARUS]*220).'"}';
					else
						echo '{"value": "0"}';
					//$echo .= "SELECT AVG(CURRENT) AS ARUS FROM MONITORING_DETAIL WHERE ID_MODUL_DEF='$idmoduldef' AND (DT>='".$harian[$i]."' AND DT<'".$harian[$i+1]."')<br>";
				}
	echo '			]
        }';
        	$a++;
			}
		}
		else if($type=="2" || $type=="3"){
			if($idmodul!="x")
        		$quemdl=mysql_query("SELECT ID_MODUL, TOKEN FROM MODUL WHERE ID_MODUL='$idmodul' AND DEL='0'");
        	else
        		$quemdl=mysql_query("SELECT ID_MODUL, TOKEN FROM MODUL WHERE DEL='0'");
        	$a=0;
        	while($datamodul=mysql_fetch_assoc($quemdl)){
        		if($a>0) echo ",";
        		$idmodul=$datamodul[ID_MODUL];
        		$que=mysql_query("SELECT D.DEVICE_NAME, D.POWER FROM MODUL_DEF MD LEFT JOIN MODUL_DEF_DET MDD ON MD.ID_MODUL_DEF=MDD.ID_MODUL_DEF LEFT JOIN DEVICE_LIST D ON MDD.ID_DEVICE=D.ID_DEVICE WHERE ID_MODUL='$idmodul' AND MD.AKTIF='1'");
				$x=0;
				while($mdl=mysql_fetch_assoc($que)){
					if($x<0) echo ", ";
					$devicedesc=$mdl[DEVICE_NAME];
					$x++;
				}	
		echo '{
        	    "seriesname": "Modul '.$datamodul[ID_MODUL].' - '.$datamodul[TOKEN].'",
            	"data": [	';
            		for($i=0;$i<count($whdaily[$idmodul]);$i++){   
						if($i>0) echo ",";
						echo '{"value": "'.($whdaily[$idmodul][$i]).'"}';
					}
		echo '			]
        	}';	
        		$a++;
				}
		}

	?>
		]
    }
	});
    fusioncharts.render();
});
</script>
<script type="text/javascript">
  var timeout = setTimeout("location.reload(true);",600000);
  function resetTimeout() {
    clearTimeout(timeout);
    timeout = setTimeout("location.reload(true);",600000);
  }
</script>
		<?php
		//echo date_format(date_create($tahun."-".$bulan."-01"),"M Y");
		//echo $days;
		//echo $a_date." -> ".$l_date." -> ".$days;
		//echo $echo;
		//print_r($arr);
}
		?>		


</center>	
<?php	
	include "footer.php";
?>

