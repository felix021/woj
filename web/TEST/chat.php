<?php
/**
 * ��ҹ·�˼���������
 * ����: heiyeluren <heiyeluren_at_163.com>
 * ����: 2005-8-10 22:42
 * �޸�: 2005-8-11 23:25
 */
error_reporting(7);
session_start();
header("ContentType:text/html;charset=gb2312");
define("SCRIPT", $_SERVER['SCRIPT_NAME']);
define("CHAT_NOTE", "./chat.txt");
define("ONLINE_LIST", "./online.txt");
define("REF_TIME", 5);
define("CHAT_NAME", "��ҹ·��������");
define("AD_MSG", "�������й����˽�, ף������˽ڿ���!!");

//��ȡֵ
if (isset($_GET['action']) && !empty($_GET['action'])) {
 $action = $_GET['action'];
}

//����Ѿ���½��ôֱ�������������
if (!isset($_GET['action']) && isset($_SESSION['username'])) {
 header("location:".SCRIPT."?action=chat");
}

//��½��ʾ
if (!isset($_GET['action']))
{
 if (!session_is_registered('username'))
 {
  echo " <p><h3 align=center>[ ".CHAT_NAME." ] &copy; 2005</h3></p>
   <p align=center>
   <form action=".SCRIPT."?action=login method=post>
   �س�: <input type=text size=25 maxlength=30 name=login_user>
   <input type=submit value=����>
   </form></p>
   ";
  exit;
 }
}

//У���½
if ($action=='login')
{
 if (isset($_POST['login_user']) && !empty($_POST['login_user'])) {
  $username = $_POST['login_user'];
 } else {
  $username = "�ο�";
 }
 session_register('username');
 save_online($username, get_client_ip());
 header("location:".SCRIPT."?action=chat");
}

//��ʼ����www.knowsky.com
if ($action=="chat")
{
 $online_sum = get_online_sum();
 echo "<head><title>[ ".CHAT_NAME." ]</title></head><center><body bgcolor=#C4BFB9 style='font-size:12px;'>
   <div style='border:1px solid #999966; width:802px;height:450'><iframe src='".SCRIPT."?action=show' name=show_win width=800 height=450 scrolling=auto frameborder=0></iframe></div><br>
   <marquee width=70% scrollamount=2> ".AD_MSG." </marquee>&nbsp;&nbsp; [��ǰ���ߣ�$online_sum]
   <iframe src='".SCRIPT."?action=say' name=say_win width=800 height=60 scrolling=no frameborder=0>
 ";
}

//˵������
if ($action=="say")
{
 echo "<head><title>[ ".CHAT_NAME." ]</title></head><center><body bgcolor=#C4BFB9 style='font-size:12px;'>
  <form action=".SCRIPT."?action=save method=post name=chat onSubmit='return check()'>
  [".$_SESSION['username']."]˵:<input type=text size=80 maxlength=500 name=chatmsg style=' background-color:#99CC99; width:550px; height:22px; border:1px solid:#000000'>
  <select name=usercolor>
  <OPTION selected style='COLOR: #000000' value='000000'>Ĭ����ɫ</OPTION>
  <OPTION style='COLOR: #000000' value='#000000'>��ɫ����</OPTION>
  <option style='COLOR: #ff0000' value='#FF0000'>��ɫ����</option>
  <option style='COLOR: #0000ff' value='#0000FF'>��ɫ����</option>
  <option style='COLOR: #ff00ff' value='#FF00FF'>��ɫ����</option>
  <option style='COLOR: #009900' value='#009900'>��ɫ�ഺ</option>
  <option style='COLOR: #009999' value='#009999'>��ɫ��ˬ</option>
  <option style='COLOR: #990099' value='#990099'>��ɫ�н�</option>
  <option style='COLOR: #990000' value='#990000'>��ҹ�˷�</option>
  <option style='COLOR: #000099' value='#000099'>��������</option>
  <option style='COLOR: #999900' value='#999900'>�����Ʒ�</option>
  <option style='COLOR: #ff9900' value='#FF9900'>�ֽ�����</option>
  <option style='COLOR: #0099ff' value='#0099FF'>��������</option>
  <option style='COLOR: #9900ff' value='#9900FF'>��������</option>
  <option style='COLOR: #ff0099' value='#FF0099'>���İ�ʾ</option>
  <option style='COLOR: #006600' value='#006600'>ī�����</option>
  <option style='COLOR: #333333' value='#333333'>��ɫ�켣</option>
  <option style='COLOR: #999999' value='#999999'>�׶ػ���</option>
  </select>
  <input type=submit value='˵��' style='background-color:#ffffff'> <a href=".SCRIPT."?action=logoff title=�˳������� target=_top onclick='return confirm(\"��ȷ��Ҫ�˳���������?\")'>�˳�</a>
  </form>
  <script>function check(){if(document.chat.chatmsg.value==''){;alert('������������Ϣ!');return false;}return true;}</script>
  ";
}

//����˵��
if ($action=="save")
{
 if ($_POST['chatmsg']!="") {
  save_chat($_POST['chatmsg'], $_SESSION['username'], $_POST['usercolor']);
 }
 header("location:".SCRIPT."?action=say");
}

//��ʾ�����¼
if ($action=="show")
{
 echo "<body style='font-size:12px' onload='scrollit()'>";
 echo "<META HTTP-EQUIV=REFRESH CONTENT='".REF_TIME.";URL=".SCRIPT."?action=show'>";
 if (file_exists(CHAT_NOTE)) {
  $chat_msg = @file_get_contents(CHAT_NOTE);
  echo $chat_msg;
 } else {
  echo "Ŀǰû����˵��";
 }
}

//�˳�������
if ($action=="logoff")
{
 unset($_SESSION['username']);
 session_destroy();
 header("location:".SCRIPT);
}

/* �������� */

//���������¼����
function save_chat($msg, $user, $color)
{
 if (!$fp = fopen(CHAT_NOTE, "a+")) {
  die('���������¼�ļ�ʧ��, �����Ƿ���Ȩ��.');
 }
 $msg = htmlspecialchars($msg);
 $msg = preg_replace('/([http|ftp:\/\/])*([a-zA-])+\.([a-zA-Z0-9_-])+\.([a-zA-Z0-9_-])+(a-zA-Z0-9_)*/', '<a href=\\0 target=_blank>\\0</a>', $msg);
 $msg = preg_replace('/([a-zA-Z0-9_\.])+@([a-zA-Z0-9-])+\.([a-zA-Z0-9-]{2,4})+/', '<a href=mailto:\\0>\\0</a>', $msg);
 $msg = date('H:i:s')." [".$user."]˵: <font color='".$color."'>".$msg."</font><br>\r\n";
 if (!fwrite($fp, $msg)) {
  die('д�������¼ʧ��.');
 }
 fclose($fp);
}
//д��������Ϣ
function save_online($user, $ip)
{
 if (!$fp = fopen(ONLINE_LIST, "a+")) {
  die("���������б��ļ�ʧ��, �����Ƿ���Ȩ��.");
 }
 $user = str_replace("|", "", $user);
 $line = $user."|".$ip."|".time()."\r\n";
 if (!fwrite($fp, $line)) {
  die("д�������б�ʧ��.");
 }
 fclose($fp);
}
//��ȡ��������
function get_online_sum()
{
 if (file_exists(ONLINE_LIST)) {
  $online_msg = file(ONLINE_LIST);
  return count($online_msg);
 } else {
  return 0;
 }
}
//��ȡ��ǰ��½�û�IP
function get_client_ip()
{
 if ($_SERVER['REMOTE_ADDR']) {
  $cip = $_SERVER['REMOTE_ADDR'];
 } elseif (getenv("REMOTE_ADDR")) {
  $cip = getenv("REMOTE_ADDR");
 } elseif (getenv("HTTP_CLIENT_IP")) {
  $cip = getenv("HTTP_CLIENT_IP");
 } else {
  $cip = "unknown";
 }
 return $cip;
}
?>
