#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/ci.class.php';
su('admin');

/**

title=测试 ciModel->sendRequest();
cid=1
pid=1

成功执行jenkins构建 >> 1
错误的密码执行jenkins构建 >> 0
空的参数执行jenkins构建 >> 1
错误的路径执行jenkins构建 >> 0

*/

$ci = new ciTest();

$url      = 'http://10.0.1.161:58080/job/dave/buildWithParameters/api/json';
$errorUrl = 'http://10.0.1.161:58080/job/test/buildWithParameters/api/json';

$userPWD  = 'admin:1196c85ba525a268570df9da627e3a7b2d';
$errorPWD = 'admin:1';

$emptyData  = new stdclass();

$normalData = new stdclass();
$normalData->PARAM_TAG = 'tag_test1';

r($ci->sendRequestTest($url,      $normalData, $userPWD))  && p() && e('1'); //成功执行jenkins构建
r($ci->sendRequestTest($url,      $normalData, $errorPWD)) && p() && e('0'); //错误的密码执行jenkins构建
r($ci->sendRequestTest($url,      $emptyData,  $userPWD))  && p() && e('1'); //空的参数执行jenkins构建
r($ci->sendRequestTest($errorUrl, $normalData, $userPWD))  && p() && e('0'); //错误的路径执行jenkins构建