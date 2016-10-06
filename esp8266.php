<?php
	error_reporting(0);
	mysql_connect('localhost','root','');
	mysql_select_db('db_syshome');	
	$key=$_GET[key];
	$arus=$_GET[arus];
	$ip=$_SERVER['REMOTE_ADDR'];
	
	$que=mysql_query("SELECT M.ID_MODUL, M.STATUS, MD.ID_MODUL_DEF FROM MODUL M LEFT JOIN MODUL_DEF MD ON M.ID_MODUL=MD.ID_MODUL WHERE M.TOKEN='$key' AND MD.AKTIF='1'");
	
	if(mysql_num_rows($que) > 0){
		$data=mysql_fetch_array($que);
		$id_modul=$data['ID_MODUL'];
		$id_modul_def=$data['ID_MODUL_DEF'];
		$status=$data['STATUS'];		
		
		$cnt=mysql_num_rows(mysql_query("SELECT * FROM ALERT WHERE ID_MODUL='".$id_modul."' AND SEEN='2'"));
		if($cnt>0)
			mysql_query("UPDATE ALERT SET SEEN=1 WHERE SEEN=2 AND ID_MODUL='".$id_modul."'");
				
		$priv1=mysql_num_rows(mysql_query("SELECT * FROM PRIVILEDGE WHERE ID_MODUL='$id_modul' AND TYPE='1' AND (CURTIME() BETWEEN HARIAN_START AND HARIAN_FINISH)"));//aktifkan harian
		$priv11=mysql_num_rows(mysql_query("SELECT * FROM PRIVILEDGE WHERE ID_MODUL='$id_modul' AND TYPE='1'"));//aktifkan harian
		$priv2=mysql_num_rows(mysql_query("SELECT * FROM PRIVILEDGE WHERE ID_MODUL='$id_modul' AND TYPE='2' AND (CURTIME() BETWEEN HARIAN_START AND HARIAN_FINISH)"));//nonaktifkan harian
		
		if($priv1 > 0){
			mysql_query("UPDATE MODUL SET STATUS='1' WHERE ID_MODUL='$id_modul'");
		}else if($priv11 > 0){
			mysql_query("UPDATE MODUL SET STATUS='0' WHERE ID_MODUL='$id_modul'");
		}else if($priv2 > 0){
			mysql_query("UPDATE MODUL SET STATUS='0' WHERE ID_MODUL='$id_modul'");
		}else{
			mysql_query("UPDATE MODUL SET STATUS='1' WHERE ID_MODUL='$id_modul'");
		}		
		
		$jumip=mysql_num_rows(mysql_query("SELECT * FROM MODUL WHERE IP_ADDR='$ip' AND TOKEN='$key'"));
		if($jumip == 0){
			mysql_query("UPDATE MODUL SET IP_ADDR='".$ip."' WHERE TOKEN='$key'");
		}		
		
		$data=mysql_fetch_array(mysql_query("SELECT ID_PRICE FROM PRICE ORDER BY LAST_UPD DESC LIMIT 0,1"));		
		$price=$data['ID_PRICE'];
		
		if($status==0){
			$arus=0;
			$data=mysql_fetch_array(mysql_query("SELECT CURRENT FROM MONITORING_DETAIL WHERE ID_MODUL_DEF='$id_modul_def' ORDER BY DT DESC LIMIT 0,1"));		
			if($data['CURRENT']>0)
				mysql_query("INSERT INTO MONITORING_DETAIL VALUES('','".$id_modul_def."','".$price."','$arus',now())");				
			echo "8";
		}
		else{
			$interval_menit=5;	//5		1
			$menit_jam=60;		//60	12
			$jams=array(true,false,false,false,false,false,false);
			$jam=-1;
			for($n=0;$n<=6;$n++){
				if($jams[$n]){
					$jam++;
					$jams[$n+1]=true;
					for($a=0;$a<12;$a++){
						$curque="SELECT AVG(CURRENT) AS ARUS FROM MONITORING_DETAIL WHERE ID_MODUL_DEF=".$id_modul_def." AND (DT>(TIMESTAMPADD(MINUTE, -".($interval_menit*($a+1)+($jam*$menit_jam)).", NOW())) AND DT<=(TIMESTAMPADD(MINUTE, -".($interval_menit*($a)+($jam*$menit_jam)).", NOW())))";
						$curque=mysql_query($curque);
						$data=mysql_fetch_assoc($curque);
						if($data[ARUS]==0)	$jams[$n+1]=false;						
					}
				}
			}
			if($jam==6){
				$curque="SELECT COUNT(*) AS WARN FROM ALERT WHERE ID_MODUL_DEF=".$id_modul_def." AND EXPL='menyala lebih dari 6 jam' AND (DT_ALERT>(TIMESTAMPADD(MINUTE, -".($menit_jam/2).", NOW())))";
				$curque=mysql_query($curque);
				$data=mysql_fetch_assoc($curque);
				if($data[WARN]==0)
					mysql_query("INSERT INTO ALERT VALUES('','$id_modul_def',now(),'menyala lebih dari 6 jam','0')");
			}			
			mysql_query("INSERT INTO MONITORING_DETAIL VALUES('','".$id_modul_def."','".$price."','".$arus."',now())");	
			echo ($jam+1);
		}		
	}
?>