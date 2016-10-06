<style type="text/css" media="print">
  @page { size: landscape; }
</style>
<?php 
	error_reporting(0);
	mysql_connect('localhost','root','');
	mysql_select_db('db_syshome');

	//echo "Datetime Print : ".date("l, d F Y")." (".date("H:i:s").")";
	
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

function detail($tgl, $idmodul){
	global $whdaily;
	
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
			$datetime1 = new DateTime($dt[$n]);
			$datetime2 = new DateTime((!empty($dt[$n+1])?$dt[$n+1]:$now));
			$interval = $datetime1->diff($datetime2);
			$selisih = $interval->format("%Y:%M:%D:%H:%I:%S");
			$waktu=explode(":",$selisih);
			$h=(($waktu[2]*24) + ($waktu[3]) + ($waktu[4]/(60)) + ($waktu[5]/(60*60))); 
			$daya=($arus[$n] * 220);
			$wh = ($h * $daya);
			$harga=$wh*$hrg[$n];
			$totwh += $wh;
			$totharga += $harga;
		}
		$whdaily[$idmodul][]=$totwh;
	}
	else{
		$whdaily[$idmodul][]=0;
	}
}
?>
		<script type="text/javascript" src="../chart/js/fusioncharts.js"></script>
		<script type="text/javascript" src="../chart/js/themes/fusioncharts.theme.fint.js?cacheBust=56"></script>
<?php
	extract($_GET);
	$whdaily=array();
	
	if($type=="1"){
		if($idmodul=="x"){
			$que=mysql_query("SELECT ID_MODUL, TOKEN FROM MODUL WHERE DEL='0'");
			while($mdl=mysql_fetch_assoc($que)){
				detail($tgl, $mdl[ID_MODUL]);
			}
		}
		else{
			detail($tgl, $idmodul);
		}
	}
	else if($type=="2")	{							
		$diff = abs(strtotime($tglmulai) - strtotime($tglsampai));
		$days=floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24))+1;
		if($idmodul=="x"){
			$que=mysql_query("SELECT ID_MODUL, TOKEN FROM MODUL WHERE DEL='0'");
			while($mdl=mysql_fetch_assoc($que)){
				$a=0;
				$totwh=0;
				$totharga=0;
				while($a<$days){
					$tgllb = date('Y-m-d', strtotime($tglmulai . '+ '.$a.'days'));
					$a++;
					detail($tgllb, $mdl[ID_MODUL]);
				}
			}
		}else{
				$que=mysql_query("SELECT ID_MODUL, TOKEN FROM MODUL WHERE ID_MODUL='$idmodul'");
				while($mdl=mysql_fetch_assoc($que)){
					$a=0;
					$totwh=0;
					$totharga=0;
					while($a<$days){
						$tgllb = date('Y-m-d', strtotime($tglmulai . '+ '.$a.'days'));
						$a++;
						detail($tgllb, $idmodul);
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
				$totwh=0;
				$totharga=0;	
				while($a<$days){
					$tgllb = date('Y-m-d', strtotime($a_date . '+ '.$a.'days'));
					$a++;					
					detail($tgllb, $mdl[ID_MODUL]);
				}
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
				$totwh=0;
				$totharga=0;
				while($a<$days){
					$tgllb = date('Y-m-d', strtotime($a_date . '+ '.$a.'days'));
					$a++;					
					detail($tgllb, $idmodul);
				}
			}
		}
	}
?>
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

	<div id="chart-container"></div>
	<br><br>
<script>
	timeout = setTimeout("window.print();",5000);
	//window.print();
</script>