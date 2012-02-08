<?php if(isset($_GET['mode']) and $_GET['mode'] == 'phpinfo') die(phpinfo());?>

<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
  <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
  <title>欢迎使用禅道集成运行环境！</title>
</head>
<body>
<h1>欢迎使用禅道集成运行环境！</h1>
<h3>关于该环境</h3>
该集成环境基于xampp usb lite版本精简而成，同时内置了禅道的应用程序。
xampp是非常优秀的apache, mysql, php集成运行环境，官方网站是：<a href='http://www.apachefriends.org/zh_cn/xampp.html' target='_blank'>http://www.apachefriends.org/zh_cn/xampp.html</a>

<h3>常用链接</h3>
<ul>
  <li><a href='/sqlbuddy/'    target='_blank'>数据库管理</a></li>
  <li><a href='?mode=phpinfo' target='_blank'>PHP运行信息</a></li>
</ul>

<h1 align='center'><a href='/zentao/'>访问禅道</a></h1>
</body>
</html>
