<?php
	include "conf/config.php";
	$request = str_replace("/syshome/", "", $_SERVER['REQUEST_URI']);
	$param = split("/", $request);
	//echo "<script>alert('".$_SESSION[ID_USER]." - ".$param[0]."');</script>";
	
	if(empty($_SESSION[ID_USER]) && $param[0]!="login"){
		echo "<script>document.location='./login'</script>";			
	}
	else if(!empty($_SESSION[ID_USER]) && $param[0]=="logout"){
		session_unset();
		session_destroy();
		echo "<script>document.location='./login'</script>";
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <title>Syshome Monitoring System</title>
    <link type="text/css" href="css/menu.css" rel="stylesheet"/>
    <script type="text/javascript" src="datetimepicker/jquery.js"></script>
    <script type="text/javascript" src="js/menu.js"></script>
</head>
<body>

<style type="text/css">
* { margin:0;
    padding:0;
}
body { background:rgb(74,81,85); }
div#menu { margin:5px auto; }
div#copyright {
    font:11px 'Trebuchet MS';
    color:rgb(74,81,85);
    text-indent:30px;
    padding:40px 0 0 0;
}
div#copyright a { color:rgb(74,81,85); }
div#copyright a:hover { color:#222; }
</style>
<?php
if($param[0]!="login"){
?>
<div id="menu">
    <ul class="menu">
        <li><a href="./" class="parent"><span> Home </span></a>
        </li>
        <li><a href="#" class="parent"><span> Data Master </span></a>
            <ul>
                <li><a href="./modul"><span> Data Modul Pengendali </span></a></li>
                <li><a href="./device"><span> Data Piranti Elektronik </span></a></li>
                <li><a href="./hargakwh"><span> Data Harga Listrik </span></a></li>
            </ul>
        </li>
        <li><a href="./def_modul"><span> Pendefinisian Modul Pengendali </span></a></li>
        <li><a href="./aturan_akses"><span> Pengaturan Akses </span></a></li>
        <li><a href="./report"><span> Laporan </span></a></li>
        <li><a href="./notif"><span> 
<?php
	$que=mysql_query("SELECT ID_MODUL FROM MODUL WHERE DEL=0");
	while($data=mysql_fetch_assoc($que)){
		$que2=mysql_query("SELECT MAX(DET.DT) AS TRAKIR, NOW() AS SAIKI, DET.ID_MODUL_DEF FROM monitoring_detail DET LEFT JOIN MODUL_DEF DEF ON DET.ID_MODUL_DEF=DEF.ID_MODUL_DEF WHERE DEF.ID_MODUL='".$data[ID_MODUL]."'");
		while($dt=mysql_fetch_assoc($que2)){
			$datetime1 = new DateTime($dt[TRAKIR]);
			$datetime2 = new DateTime($dt[SAIKI]);
			$interval = $datetime1->diff($datetime2);
			$selisih = $interval->format("%Y:%M:%D:%H:%I:%S");
			$waktu=explode(":",$selisih);			
			$h=(($waktu[2]*24) + ($waktu[3]) + ($waktu[4]/(60)) + ($waktu[5]/(60*60))); 
			//echo "<script>alert('".$h."')</script>";
			if($h>0.5){
				$cnt=mysql_num_rows(mysql_query("SELECT * FROM ALERT WHERE ID_MODUL='".$data[ID_MODUL]."' AND SEEN='2'"));
				if($cnt==0)
					mysql_query("INSERT INTO ALERT VALUES('','".$data[ID_MODUL]."',now(),'tidak terhubung dengan sistem selama lebih dari 30 menit','2')");
			}
			//else
			//	mysql_query("UPDATE ALERT SET SEEN=1 WHERE SEEN=2 AND ID_MODUL='".$data[ID_MODUL]."'");
		}
	}


	$que=mysql_query("SELECT * FROM ALERT WHERE SEEN='0' OR SEEN='2'");
	$notif=mysql_num_rows($que);
	if($notif>0)
		echo '<font color="red"><b>Pemberitahuan ( '.$notif.' )</b></font>';
	else
		echo 'Pemberitahuan'
?>		
		</span></a></li>
        <li class="last"><a href="./logout"><span>Logout</span></a></li>
    </ul>
</div>
<?php
}
?>
<br><br>