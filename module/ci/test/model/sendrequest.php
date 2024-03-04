#!/usr/bin/env php
<?php

/**

title=测试 ciModel->sendRequest();
timeout=0
cid=1

- 成功执行jenkins构建 @1
- 错误的密码执行jenkins构建 @0
- 空的参数执行jenkins构建 @1
- 错误的路径执行jenkins构建 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/ci.class.php';
su('admin');

$ci = new ciTest();

$url      = 'https://jenkinsdev.qc.oop.cc/job/paramsJob/buildWithParameters/api/json';
$errorUrl = 'https://jenkinsdev.qc.oop.cc/job/test/buildWithParameters/api/json';

$userPWD  = 'jenkins:11eb8b38c99143c7c6d872291e291abff4';
$errorPWD = 'admin:1';

$emptyData  = new stdclass();

$normalData = new stdclass();
$normalData->PARAM_TAG = 'tag_test1';

r($ci->sendRequestTest($url,      $normalData, $userPWD))  && p() && e('1'); // 成功执行jenkins构建
r($ci->sendRequestTest($url,      $normalData, $errorPWD)) && p() && e('0'); // 错误的密码执行jenkins构建
r($ci->sendRequestTest($url,      $emptyData,  $userPWD))  && p() && e('1'); // 空的参数执行jenkins构建
r($ci->sendRequestTest($errorUrl, $normalData, $userPWD))  && p() && e('0'); // 错误的路径执行jenkins构建