<?php
session_start();

echo 'hello'.$_SESSION['user_id'];

if (isset($_SESSION['count']))
	$_SESSION['count']++;
else
	$_SESSION['count']=1;

	echo "��һ�������˱�ҳ".$_SESSION['count']."��";
?>