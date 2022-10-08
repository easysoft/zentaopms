#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 gitlabModel::apiGetCurrentUser();
cid=1
pid=1

使用空的数据获取用户信息 >> return false
使用错误的host获取用户信息 >> return null
使用正确的host,错误的token获取用户信息 >> 401 Unauthorized
通过host,token获取用户信息 >> 1
通过host,权限不足的token获取用户信息 >> insufficient_scope

*/

$gitlab = $tester->loadModel('gitlab');

$host      = '';
$token     = '';

$result = $gitlab->apiGetCurrentUser($host, $token);
if($result === false) $result = 'return false';
r($result) && p() && e('return false'); //使用空的数据获取用户信息

$host   = 'http://10.0.1.161:5108';
$result = $gitlab->apiGetCurrentUser($host, $token);
if(!$result) $result = 'return null';
r($result) && p() && e('return null'); //使用错误的host获取用户信息

$host   = 'http://10.0.7.242:9980';
$result = $gitlab->apiGetCurrentUser($host, $token);
r($gitlab->apiGetCurrentUser($host, $token)) && p('message') && e('401 Unauthorized'); //使用正确的host,错误的token获取用户信息

$token  = 'y2UBqwPPzaLxsniy8R6A';
$result = $gitlab->apiGetCurrentUser($host, $token);
r($result) && p('id') && e('1'); //通过host,token获取用户信息

$token  = 'wVFHE6NZA-cJy-3U2y2J';
$result = $gitlab->apiGetCurrentUser($host, $token);
r($result) && p('error') && e('insufficient_scope'); //通过host,权限不足的token获取用户信息
