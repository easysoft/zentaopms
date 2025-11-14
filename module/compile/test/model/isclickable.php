#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
/**

title=测试 repoModel::isClickable();
timeout=0
cid=15753

- 计算状态为空时是否能查看日志 @1
- 计算状态为success时是否能查看结果 @0
- 计算状态为failture时是否能重试 @0
- 计算状态为error时是否能重试 @0
- 计算测试单存在时是否能查看结果 @1
*/

$compileModel = $tester->loadModel('compile');

$compile1 = new stdclass();
$compile1->status = '';

$compile2 = new stdclass();
$compile2->status = 'success';

$compile3 = new stdclass();
$compile3->status = 'failture';

$compile4 = new stdclass();
$compile4->testtask = 0;

$compile5 = new stdclass();
$compile5->testtask = 1;
r($compileModel->isClickable($compile1, 'log'))    && p() && e('1'); //计算状态为空时是否能查看日志
r($compileModel->isClickable($compile2, 'result')) && p() && e('0'); //计算状态为success时是否能查看结果
r($compileModel->isClickable($compile3, 'result')) && p() && e('0'); //计算状态为failture时是否能查看结果
r($compileModel->isClickable($compile4, 'result')) && p() && e('0'); //计算测试单不存在时是否能查看结果
r($compileModel->isClickable($compile5, 'result')) && p() && e('1'); //计算测试单存在时是否能查看结果
