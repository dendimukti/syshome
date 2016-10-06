<?php
	include "header.php";
?>
<center>
	<font color="white">
	<h2> Selamat Datang</h2>
	<br>
<?php
	$que=mysql_query("SELECT * FROM ALERT WHERE SEEN='0' OR SEEN='2'");
	$notif=mysql_num_rows($que);
	if($notif>0){
		echo "Anda memiliki ".$notif." pemberitahuan terbaru, silahkan klik <a href='./notif'>disini</a> untuk melihat pemberitahuan<br>";
	}else{
		echo "";
	}
	
?>	
	</font>
</center>	
<?php	
	include "footer.php";
?>