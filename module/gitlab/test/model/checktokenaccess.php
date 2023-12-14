#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 gitlabModel::checkTokenAccess();
timeout=0
cid=1

- 使用空的数据验证token权限 @return false
- 使用错误的host验证token权限 @return false
- 使用正确的host,错误的token验证token权限 @return null
- 通过host,token验证token权限 @success
- 通过host,权限不足的token验证token权限 @no access

*/

$gitlab = $tester->loadModel('gitlab');

$host      = '';
$token     = '';

$result = $gitlab->checkTokenAccess($host, $token);
if($result === false) $result = 'return false';
r($result) && p() && e('return false'); //使用空的数据验证token权限

$host   = 'http://10.0.1.161:5108';
$result = $gitlab->checkTokenAccess($host, $token);
if(!$result) $result = 'return false';
r($result) && p() && e('return false'); //使用错误的host验证token权限

$host   = 'http://10.0.7.242:9980';
$result = $gitlab->checkTokenAccess($host, $token);
if(empty($result)) $result = 'return null';
r($result) && p() && e('return null'); //使用正确的host,错误的token验证token权限

$token  = 'x88fZokrp5hShia2jyBN';
$result = $gitlab->checkTokenAccess($host, $token);
if(isset($result->id)) $result = 'success';
r($result) && p() && e('success'); //通过host,token验证token权限

$token  = 'wVFHE6NZA-cJy-3U2y2J';
$result = $gitlab->checkTokenAccess($host, $token);
if(!isset($result->id)) $result = 'no access';
r($result) && p() && e('no access'); //通过host,权限不足的token验证token权限