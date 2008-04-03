<?php
include_once('../../common/tcpclient.php');

class problem_list_t
{

	var $pagenum;
	var $user_id;
	var $title;
	var $source;
	var $result = array();

	function problem_list_t($pagenum='1', $user_id='?', $title='?', $source='?')
	{
		$this->pagenum = $pagenum;
		$this->user_id = $user_id;
		$this->title = $title;
		$this->source = $source;
	}
	function setPagenum($pagenum)
	{
		$this->pagenum = $pagenum;
	}
	function setUser_id($user_id)
	{
		$this->user_id = $user_id;
	}
	function setTitle($title)
	{
		$this->title = $title;
	}
	function setSource($source)
	{
		$this->source = $source;
	}


	function getResult_type($i)
	{
		return $result[$i*6];
	}
	function getProblem_id($i)
	{
		return $result[$i*6+1];
	}
	function getTitle($i)
	{
		return $result[$i*6+2];
	}
	function getSource($i)
	{
		return $result[$i*6+3];
	}
	function getAC($i)
	{
		return $result[$i*6+4];
	}
	function getTotal($i)
	{
		return $result[$i*6+5];
	}

	function getRow()
	{
		return count($this->result) / 6;
	}

	function getResult()
	{
		$d = "\001";
		$message = $this->pagenum.$d.$this->user_id.$d.$this->title.$d.$this->source;
		$header = sprintf("%s%08d", "pl", strlen($message));

		$tc = new TCPClient();
		$tc->create() or die("unable to create socket!");
		$tc->connect() or die("unable to connect to server!");
		$tc->sendstr($header) or die("send header failed");
		$tc->sendstr($message)or die("send message failed");
		$recv= $tc->recvstr(10);
		$len = sscanf($recv, "%d");
		if($len > 0){
			$recv = $tc->recvstr($len);
			$this->result = explode($d, $recv);
		}
		else
			$this->result = null;
		$tc->close;

	}

}

/*
$pl = new problem_list_t();
$pl->getResult();
*/
?>