#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/bug.class.php';

/**

title=bugModel->resolve();
cid=1
pid=1

*/

$emptyTitle = new stdclass();
$emptyTitle->title       = '';
$emptyTitle->openedBuild = 1;

$emptyBuild = new stdclass();
$emptyBuild->title       = '111';
$emptyBuild->openedBuild = 0;

$emptyItem = new stdclass();

$emptyAll = new stdclass();
$emptyAll->title       = '';
$emptyAll->openedBuild = 0;
$emptyAll->product     = 0;
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

$hasTitleBuild = new stdclass();
$hasTitleBuild->title       = '11';
$hasTitleBuild->openedBuild = 1;
$hasTitleBuild->product     = 0;
$hasTitleBuild->branch      = 0;
$hasTitleBuild->module      = 0;
$hasTitleBuild->project     = 0;
$hasTitleBuild->execution   = 0;
$hasTitleBuild->deadline    = '';
$hasTitleBuild->steps       = '';
$hasTitleBuild->type        = '';
$hasTitleBuild->pri         = 0;
$hasTitleBuild->severity    = 0;
$hasTitleBuild->keywords    = '';

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

$bugs1 = array('emptyTitle' => $emptyTitle);
$bugs2 = array('emptyBuild' => $emptyBuild);
$bugs3 = array('emptyTitle' => $emptyTitle, 'emptyBuild' => $emptyBuild);
$bugs4 = array('emptyItem' => $emptyItem);
$bugs5 = array('emptyAll' => $emptyAll);
$bugs6 = array('emptyItem' => $emptyItem, 'emptyAll' => $emptyAll);
$bugs7 = array('hasTitleBuild' => $hasTitleBuild, 'narmal1' => $normal1, 'narmal2' => $normal2);

$bug = new bugTest();

r($bug->checkBugsForBatchCreateTest($bugs1)) && p() && e('『Bug标题』不能为空。');                                                                  // 测试检查 不输入标题 的bugs
r($bug->checkBugsForBatchCreateTest($bugs2)) && p() && e('『影响版本』不能为空。');                                                                 // 测试检查 不输入版本 的bugs
r($bug->checkBugsForBatchCreateTest($bugs3)) && p() && e('『Bug标题』不能为空。『影响版本』不能为空。');                                            // 测试检查 不输入标题 不输入版本 的bugs
r($bug->checkBugsForBatchCreateTest($bugs4)) && p() && e('『Bug标题』不能为空。『影响版本』不能为空。');                                            // 测试检查 空对象 的bugs
r($bug->checkBugsForBatchCreateTest($bugs5)) && p() && e('『Bug标题』不能为空。『影响版本』不能为空。');                                            // 测试检查 不输入任务字段 的bugs
r($bug->checkBugsForBatchCreateTest($bugs6)) && p() && e('『Bug标题』不能为空。『影响版本』不能为空。『Bug标题』不能为空。『影响版本』不能为空。'); // 测试检查 空对象 不输入任务字段 的bugs
r($bug->checkBugsForBatchCreateTest($bugs7)) && p() && e('hasTitleBuild,narmal1,narmal2');                                                          // 测试检查 有标题和版本 两个有所有字段 的bugs
