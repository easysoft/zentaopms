<?php
error_reporting(0);

$config->langs['cn'] = '中文简体';
$config->langs['en'] = 'EN';

$lang->cn->title     = '欢迎使用禅道集成运行环境！';
$lang->cn->poweredBy = "由<a href='http://www.apachefriends.org/' target='_blank'>xampp</a>精简而来";

$lang->cn->links['zentao']['link']   = '/zentao/';
$lang->cn->links['zentao']['text']   = '访问禅道';
$lang->cn->links['official']['link'] = 'http://www.zentao.net/';
$lang->cn->links['official']['text'] = '禅道官网';
$lang->cn->links['sqlbudyy']['link'] = '/sqlbuddy/';
$lang->cn->links['sqlbudyy']['text'] = '数据库管理';
$lang->cn->links['phpinfo']['link']  = '?mode=phpinfo';
$lang->cn->links['phpinfo']['text']  = 'PHP信息';

$lang->en->title     = 'Welcome to use zentao!';
$lang->en->poweredBy = "reduced from <a href='http://www.apachefriends.org/' target='_blank'>xampp</a>";

$lang->en->links['zentao']['link']   = '/zentao/';
$lang->en->links['zentao']['text']   = 'ZenTao';
$lang->en->links['official']['link'] = 'http://www.zentao.net/';
$lang->en->links['official']['text'] = 'Community';
$lang->en->links['sqlbudyy']['link'] = '/sqlbuddy/';
$lang->en->links['sqlbudyy']['text'] = 'MySQL';
$lang->en->links['phpinfo']['link']  = '?mode=phpinfo';
$lang->en->links['phpinfo']['text']  = 'PHP';

if(is_file('./my.php')) include './my.php';

$acceptLang = stripos($_SERVER['HTTP_ACCEPT_LANGUAGE'], 'zh-CN') !== false ? 'cn' : 'en';
$acceptLang = isset($_GET['lang']) ? $_GET['lang'] : $acceptLang;
$clientLang = $lang->$acceptLang;
?>

<?php if(isset($_GET['mode']) and $_GET['mode'] == 'phpinfo') die(phpinfo());?>
<?php include '../zentao/lib/front/front.class.php';?>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
  <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
  <meta http-equiv="refresh" content="5; url=/zentao/" />
  <title><?php echo $clientLang->title;?></title>
  <link href="/zentao/theme/default/style.css" rel="stylesheet" type="text/css">
  <link href="/zentao/theme/default/yui.css" rel="stylesheet" type="text/css">
  <style>
    html {background-color:#06294e;}
    table{margin-top:200px; background:white; border:none}
    tr, th, td{border:none}
    #welcome{font-size:20px}
    #logo{width:120px; border-right:1px solid #efefef}
    #links{padding-left:25px}
    #power{background:#efefef}
    .button-c{width:100px}
  </style>
</head>
<body>
<table class='table-5' align='center'>
  <tr class='colhead'><th colspan='2' id='welcome'><?php echo $clientLang->title;?></th></tr>
  <tr>
    <td id='logo'><img src='/zentao/theme/default/images/main/logo2.png' /></td>
    <td id='links'>
      <?php foreach($clientLang->links as $link) echo html::linkButton($link['text'], $link['link']);?>
    </td>
  </tr>   
  <tr id='power'>
    <td class='a-right' colspan='2'>
    <?php 
    echo $clientLang->poweredBy . ' ';
    foreach($config->langs as $langCode => $langName) echo html::a("?lang=$langCode", $langName);
    ?>
    </td>
  </tr>
</table>
</body>
</html>
