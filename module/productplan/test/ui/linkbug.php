#!/usr/bin/env php
<?php

/**
title=计划关联bug
timeout=0
cid=0

- 检查正常关联bug
 - 测试结果 @关联Bug成功
 - 最终测试状态 @SUCCESS
- 检查移除单个bug
 - 测试结果 @移除单个Bug成功
 - 最终测试状态 @SUCCESS
- 检查移除全部bug
 - 测试结果 @移除全部Bug成功
 - 最终测试状态 @SUCCESS
*/
chdir(__DIR__);
include '../lib/linkbug.ui.class.php';

zendata('product')->loadYaml('product', false, 2)->gen(10);
zendata('productplan')->loadYaml('productplan', false, 2)->gen(10);
$bug = zenData('bug');
$bug->project->range('0');
$bug->execution->range('0');
$bug->gen(10);

$tester = new linkBugTester();
$tester->login();
$planID['planID'] = '2';

r($tester->linkBug($planID))      && p('message,status') && e('关联Bug成功,SUCCESS');//关联Bug
r($tester->unlinkBug($planID))    && p('message,status') && e('移除单个Bug成功,SUCCESS');//移除单个Bug
r($tester->unlinkAllBug($planID)) && p('message,status') && e('移除全部Bug成功,SUCCESS');//移除全部Bug
$tester->closeBrowser();
