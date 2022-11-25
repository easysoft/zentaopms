#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 gogsModel::checkTokenAccess();
cid=1
pid=1

*/

$gogs = $tester->loadModel('gogs');

$host      = '';
$token     = '';

$result = $gogs->checkTokenAccess($host, $token);
if($result === false) $result = 'return false';
r($result) && p() && e('return false'); //使用空的数据验证token权限

$host   = 'http://10.0.7.242:9021';
$result = $gogs->checkTokenAccess($host, $token);
if(!$result) $result = 'return false';
r($result) && p() && e('return false'); //使用错误的host验证token权限

$host   = 'http://10.0.7.242:9021';
$result = $gogs->checkTokenAccess($host, $token);
if(empty($result)) $result = 'return null';
r($result) && p() && e('return null'); //使用正确的host,错误的token验证token权限

$token  = '9ff43f9d1a369465bcf0781a3785f46bcef782d1';
$result = $gogs->checkTokenAccess($host, $token);
if(isset($result->id)) $result = 'success';
r($result) && p() && e('1'); //通过host,token验证token权限

$token  = 'wVFHE6NZA-cJy-3U2y2J';
$result = $gogs->checkTokenAccess($host, $token);
if(!isset($result->id)) $result = 'no access';
r($result) && p() && e('no access'); //通过host,权限不足的token验证token权限
