#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 giteaModel::checkTokenAccess();
cid=1
pid=1

*/

$gitea = $tester->loadModel('gitea');

$host      = '';
$token     = '';

$result = $gitea->checkTokenAccess($host, $token);
if($result === false) $result = 'return false';
r($result) && p() && e('return false'); //使用空的数据验证token权限

$host   = 'http://10.0.7.242:9020';
$result = $gitea->checkTokenAccess($host, $token);
if(!$result) $result = 'return false';
r($result) && p() && e('return false'); //使用错误的host验证token权限

$host   = 'http://10.0.7.242:9020';
$result = $gitea->checkTokenAccess($host, $token);
if(empty($result)) $result = 'return null';
r($result) && p() && e('return null'); //使用正确的host,错误的token验证token权限

$token  = 'c6769e6761a7d719129b2421dcb3112d936e2b1f';
$result = $gitea->checkTokenAccess($host, $token);
r($result) && p() && e('1'); //通过host,token验证token权限

$token  = 'wVFHE6NZA-cJy-3U2y2J';
$result = $gitea->checkTokenAccess($host, $token);
if(!isset($result->id)) $result = 'no access';
r($result) && p() && e('no access'); //通过host,权限不足的token验证token权限
