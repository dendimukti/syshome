<?php
	mysql_connect('localhost','root','');
	mysql_select_db('db_syshome');
	
	function acak($jum){
		$dat=array("0","1","2","3","4","5","6","7","8","9","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
		$data="";
		for($i=0;$i<$jum;$i++){
			$data .= $dat[rand(0,35)];
		}
		return $data;
	}
	
	function ribuan($val){
		$hasil="";
		$jml=strlen($val);
		$rb=0;
		
		$vals="";
		for($i=0;$i<$jml;$i++){
			$vals = substr($val,$i,1) . $vals;
		}
		
		for($i=0;$i<$jml;$i++){			
			if($rb==3){
				$hasil = "." . $hasil;
				$rb=0;
			}
			$hasil = substr($vals,$i,1) . $hasil;
			$rb++;
		}
		return $hasil;
	}
//	$x=98765421;
//	echo $x."<br>";
//	echo ribuan($x);
?>
