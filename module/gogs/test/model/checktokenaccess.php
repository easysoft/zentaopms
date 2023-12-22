#!/usr/bin/env php
<?php

/**

title=测试 gogsModel::checkTokenAccess();
timeout=0
cid=1

- 使用空的数据验证token权限 @0
- 使用错误的host验证token权限 @0
- 使用正确的host,错误的token验证token权限 @0
- 通过host,token验证token权限 @1
- 通过host,权限不足的token验证token权限 @no access

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

global $tester;
$gogs = $tester->loadModel('gogs');

$host  = '';
$token = 'token';

r($gogs->checkTokenAccess($host, $token)) && p() && e('0'); //使用空的数据验证token权限

$host = 'https://dev.qc.oop.cc';
r($gogs->checkTokenAccess($host, $token)) && p() && e('0'); //使用错误的host验证token权限

$host = 'https://gogsdev.qc.oop.cc';
r($gogs->checkTokenAccess($host, $token)) && p() && e('0'); //使用正确的host,错误的token验证token权限

$token = '6aafc3d332b70312a680fedf26039c67e98cfabe';
r($gogs->checkTokenAccess($host, $token)) && p() && e('1'); //通过host,token验证token权限

$token  = 'wVFHE6NZA-cJy-3U2y2J';
$result = $gogs->checkTokenAccess($host, $token);
if(!isset($result->id)) $result = 'no access';
r($result) && p() && e('no access'); //通过host,权限不足的token验证token权限