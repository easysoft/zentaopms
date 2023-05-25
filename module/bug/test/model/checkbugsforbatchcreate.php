#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/bug.class.php';

/**

title=bugModel->checkBugsForBatchCreate();
cid=1
pid=1

*/

$emptyModule = new stdclass();
$emptyModule->title       = '一个标题';
$emptyModule->openedBuild = 1;
$emptyModule->module      = 0;

$emptyBuild = new stdclass();
$emptyBuild->title       = '111';
$emptyBuild->openedBuild = 0;
$emptyBuild->module      = 1;

$emptyItem = new stdclass();
$emptyItem->title       = '';
$emptyItem->module      = 0;
$emptyItem->openedBuild = 0;

$productID = 1;

$emptyAll = new stdclass();
$emptyAll->title       = '';
$emptyAll->openedBuild = 0;
$emptyAll->product     = 1;
$emptyAll->branch      = 0;
$emptyAll->module      = 0;
$emptyAll->project     = 0;
$emptyAll->execution   = 0;
$emptyAll->deadline    = '';
$emptyAll->steps       = '';
$emptyAll->type        = '';
$emptyAll->pri         = 0;
$emptyAll->severity    = 0;
$emptyAll->keywords    = '';

$hasModuleBuild = new stdclass();
$hasModuleBuild->title       = '11';
$hasModuleBuild->openedBuild = 1;
$hasModuleBuild->product     = 1;
$hasModuleBuild->branch      = 0;
$hasModuleBuild->module      = 1;
$hasModuleBuild->project     = 0;
$hasModuleBuild->execution   = 0;
$hasModuleBuild->deadline    = '';
$hasModuleBuild->steps       = '';
$hasModuleBuild->type        = '';
$hasModuleBuild->pri         = 0;
$hasModuleBuild->severity    = 0;
$hasModuleBuild->keywords    = '';

$normal1 = new stdclass();
$normal1->title       = '一个标题';
$normal1->openedBuild = 1;
$normal1->product     = 1;
$normal1->branch      = 0;
$normal1->module      = 1;
$normal1->project     = 1;
$normal1->execution   = 11;
$normal1->deadline    = '2023-01-01';
$normal1->steps       = '一个步骤';
$normal1->type        = 'codeerror';
$normal1->pri         = 1;
$normal1->severity    = 1;
$normal1->keywords    = '关键词1';


$normal2 = new stdclass();
$normal2->title       = '标题';
$normal2->openedBuild = 1;
$normal2->product     = 1;
$normal2->branch      = 0;
$normal2->module      = 1;
$normal2->project     = 1;
$normal2->execution   = 11;
$normal2->deadline    = '2023-01-01';
$normal2->steps       = '步骤';
$normal2->type        = 'config';
$normal2->pri         = 1;
$normal2->severity    = 1;
$normal2->keywords    = '关键词';

$bugs1 = array('emptyModule' => $emptyModule);
$bugs2 = array('emptyBuild' => $emptyBuild);
$bugs3 = array('emptyModule' => $emptyModule, 'emptyBuild' => $emptyBuild);
$bugs4 = array('emptyItem' => $emptyItem);
$bugs5 = array('emptyAll' => $emptyAll);
$bugs6 = array('emptyItem' => $emptyItem, 'emptyAll' => $emptyAll);
$bugs7 = array('hasModuleBuild' => $hasModuleBuild, 'narmal1' => $normal1, 'narmal2' => $normal2);

$bug = new bugTest();

global $tester;
$tester->config->bug->create->requiredFields .= ',module';

r($bug->checkBugsForBatchCreateTest($bugs1, $productID)) && p() && e('『所属模块』不能为空。');                                                                   // 测试检查 不输入模块 的bugs
r($bug->checkBugsForBatchCreateTest($bugs2, $productID)) && p() && e('『影响版本』不能为空。');                                                                   // 测试检查 不输入版本 的bugs
r($bug->checkBugsForBatchCreateTest($bugs3, $productID)) && p() && e('『所属模块』不能为空。『影响版本』不能为空。');                                             // 测试检查 不输入模块 不输入版本 的bugs
r($bug->checkBugsForBatchCreateTest($bugs4, $productID)) && p() && e('『影响版本』不能为空。『所属模块』不能为空。');                                             // 测试检查 空对象 的bugs
r($bug->checkBugsForBatchCreateTest($bugs5, $productID)) && p() && e('『影响版本』不能为空。『所属模块』不能为空。');                                             // 测试检查 不输入任务字段 的bugs
r($bug->checkBugsForBatchCreateTest($bugs6, $productID)) && p() && e('『影响版本』不能为空。『所属模块』不能为空。『影响版本』不能为空。『所属模块』不能为空。'); // 测试检查 空对象 不输入任务字段 的bugs
r($bug->checkBugsForBatchCreateTest($bugs7, $productID)) && p() && e('hasModuleBuild,narmal1,narmal2');                                                           // 测试检查 有模块和版本 两个有所有字段 的bugs
