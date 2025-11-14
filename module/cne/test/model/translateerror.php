#!/usr/bin/env php
<?php

/**

title=测试 cneModel::translateError();
timeout=0
cid=15631

- 执行cneTest模块的translateErrorTest方法，参数是$apiResult1, false 属性message @请求集群接口失败
- 执行cneTest模块的translateErrorTest方法，参数是$apiResult2, false 属性message @服务不存在
- 执行cneTest模块的translateErrorTest方法，参数是$apiResult3, false 属性message @证书与域名不匹配
- 执行cneTest模块的translateErrorTest方法，参数是$apiResult4, false 属性message @CNE服务器出错
- 执行cneTest模块的translateErrorTest方法，参数是$apiResult5, true 属性message @请求集群接口失败 [400]: [Bad Request]
- 执行cneTest模块的translateErrorTest方法，参数是$apiResult6, false 属性message @请求集群接口失败
- 执行$apiResult7属性message @服务不存在

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

$cneTest = new cneTest();

// 测试步骤1: 已知错误码400的翻译
$apiResult1 = new stdclass();
$apiResult1->code = 400;
$apiResult1->message = 'Bad Request';
r($cneTest->translateErrorTest($apiResult1, false)) && p('message') && e('请求集群接口失败');

// 测试步骤2: 已知错误码404的翻译
$apiResult2 = new stdclass();
$apiResult2->code = 404;
$apiResult2->message = 'Not Found';
r($cneTest->translateErrorTest($apiResult2, false)) && p('message') && e('服务不存在');

// 测试步骤3: 已知错误码40004的翻译
$apiResult3 = new stdclass();
$apiResult3->code = 40004;
$apiResult3->message = 'Certificate domain mismatch';
r($cneTest->translateErrorTest($apiResult3, false)) && p('message') && e('证书与域名不匹配');

// 测试步骤4: 未知错误码999的翻译(返回默认消息)
$apiResult4 = new stdclass();
$apiResult4->code = 999;
$apiResult4->message = 'Unknown error';
r($cneTest->translateErrorTest($apiResult4, false)) && p('message') && e('CNE服务器出错');

// 测试步骤5: 开启debug模式时包含原始错误信息
$apiResult5 = new stdclass();
$apiResult5->code = 400;
$apiResult5->message = 'Bad Request';
r($cneTest->translateErrorTest($apiResult5, true)) && p('message') && e('请求集群接口失败 [400]: [Bad Request]');

// 测试步骤6: 关闭debug模式时不包含原始错误信息
$apiResult6 = new stdclass();
$apiResult6->code = 400;
$apiResult6->message = 'Bad Request';
r($cneTest->translateErrorTest($apiResult6, false)) && p('message') && e('请求集群接口失败');

// 测试步骤7: 验证apiResult的message字段被正确修改
$apiResult7 = new stdclass();
$apiResult7->code = 404;
$apiResult7->message = 'Original Message';
$cneTest->translateErrorTest($apiResult7, false);
r($apiResult7) && p('message') && e('服务不存在');