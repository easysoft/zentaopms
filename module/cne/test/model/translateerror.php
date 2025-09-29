#!/usr/bin/env php
<?php

/**

title=测试 cneModel::translateError();
timeout=0
cid=0

- 执行cneTest模块的translateErrorTest方法，参数是$apiResult1, false 
 - 属性code @400
 - 属性message @请求集群接口失败
- 执行cneTest模块的translateErrorTest方法，参数是$apiResult2, false 
 - 属性code @999
 - 属性message @CNE服务器出错
- 执行$result3->message, '[404]:') !== false @rue
- 执行message, '[500]:') !== false && strpos($result4模块的message, '[Internal Server Error]') !== false方法  @rue
- 执行cneTest模块的translateErrorTest方法，参数是$apiResult5, false 
 - 属性code @41001
 - 属性message @证书过期

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

su('admin');

$cneTest = new cneTest();

// 测试步骤1：已知错误码400的翻译
$apiResult1 = new stdclass();
$apiResult1->code = 400;
$apiResult1->message = 'Bad Request';
r($cneTest->translateErrorTest($apiResult1, false)) && p('code,message') && e('400,请求集群接口失败');

// 测试步骤2：未知错误码999的翻译
$apiResult2 = new stdclass();
$apiResult2->code = 999;
$apiResult2->message = 'Unknown Error';
r($cneTest->translateErrorTest($apiResult2, false)) && p('code,message') && e('999,CNE服务器出错');

// 测试步骤3：调试模式下已知错误码的翻译
$apiResult3 = new stdclass();
$apiResult3->code = 404;
$apiResult3->message = 'Not Found';
$result3 = $cneTest->translateErrorTest($apiResult3, true);
r(strpos($result3->message, '[404]:') !== false) && p() && e(true);

// 测试步骤4：调试模式下未知错误码的翻译
$apiResult4 = new stdclass();
$apiResult4->code = 500;
$apiResult4->message = 'Internal Server Error';
$result4 = $cneTest->translateErrorTest($apiResult4, true);
r(strpos($result4->message, '[500]:') !== false && strpos($result4->message, '[Internal Server Error]') !== false) && p() && e(true);

// 测试步骤5：证书相关错误码41001的翻译
$apiResult5 = new stdclass();
$apiResult5->code = 41001;
$apiResult5->message = 'Certificate Expired';
r($cneTest->translateErrorTest($apiResult5, false)) && p('code,message') && e('41001,证书过期');