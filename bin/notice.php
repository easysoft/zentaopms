<?php
$notice = <<<EOD
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dli'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
  <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
  <title>Error</title>
</head>
<body>
<h2 style='color:red;text-align:center'>人数超出限制</h2>
您版本的用户数是{\$properties['user']['value']}，您目前系统中已有{\$user->count}人，已经超过了限制，请联系我们增加人数授权。<br>
email：<a href='mailto:co@zentao.net'>co@zentao.net</a><br>
电话：4006 889923<br />
网址：<a href='http://www.zentao.net/goto.php?item=buypro'>www.zentao.net</a><br />
<br /><br /><br />
<h2 style='color:red;text-align:center'>Accounts has exceed the limit.</h2>
The accounts has exceed the limit of {\$properties['user']['value']} peoples, please contact us to buy more licenses.<br />
email:<a href='mailto:co@zentao.net'>co@zentao.net</a><br />
Web:<a href='http://www.zentao.net/en/'>www.zentao.net/en/</a><br />
</body>
</html>
EOD;

$limitUser =<<<EOD
if(function_exists('ioncube_license_properties')) \$properties = ioncube_license_properties();
\$user = \$this->dao->select("COUNT('*') as count")->from(TABLE_USER)->where('deleted')->eq(0)->fetch();
if(!empty(\$properties['user']) and \$properties['user']['value'] < \$user->count) die("$notice");
EOD;

$limitFunc =<<<EOD
public function __construct()
{
    parent::__construct();
    $limitUser
}
EOD;

$noLoader = "
\\\$link = is_file(\\\$_SERVER['DOCUMENT_ROOT'] . '/loader-wizard.php') ? '/loader-wizard.php' : 'http://www.ioncube.com/lw/';
echo \\\"<html>
  <head>
    <meta http-equiv='content-type' content='text/html; charset=utf-8' />
    <title>error</title>
  </head>
  <body>
    <h2 style='color:red;text-align:center'>未安装 ioncube loader</h2>
    <p>您需要安装ioncube loader才能使用该功能， 使用<a href='\\\$link'>ioncube loader安装向导</a>。</p>
    <br /><br /><br />
    <h2 style='color:red;text-align:center'>Ioncube loader doesn't installed.</h2>
    <p>You haven't installed ioncube loader extension, please visit <a href='\\\$link'>the install wizard</a> to install it.</p>
  </body>
</html>\\\";
exit;
";
