#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 gitlabModel::apiGetCurrentUser();
timeout=0
cid=1

- 使用空的数据获取用户信息 @return false
- 使用错误的host获取用户信息 @return null
- 使用正确的host,错误的token获取用户信息属性message @401 Unauthorized
- 通过host,token获取用户信息属性id @1
- 通过host,权限不足的token获取用户信息属性error @insufficient_scope

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

$host   = 'https://gitlabdev.qc.oop.cc';
$result = $gitlab->apiGetCurrentUser($host, $token);
r($gitlab->apiGetCurrentUser($host, $token)) && p('message') && e('401 Unauthorized'); //使用正确的host,错误的token获取用户信息

$token  = 'glpat-b8Sa1pM9k9ygxMZYPN6w';
$result = $gitlab->apiGetCurrentUser($host, $token);
r($result) && p('id') && e('1'); //通过host,token获取用户信息

$token  = 'glpat-NqAvs1dRxHAvr2tskCZV';
$result = $gitlab->apiGetCurrentUser($host, $token);
r($result) && p('error') && e('insufficient_scope'); //通过host,权限不足的token获取用户信息