#!/usr/bin/env php
<?php
chdir(__DIR__);
include '../lib/ui/createbug.ui.class.php';

/**

title=批量编辑bug测试
timeout=0
cid=1

- 批量编辑bug
 - 测试结果 @批量编辑bug成功
 - 最终测试状态 @SUCCESS

*/
$tester = new createBugTester();
zenData('product')->loadYaml('product')->gen(1);
zenData('project')->loadYaml('execution')->gen(1);
$story = zenData('story');
$story->id->range(2);
$story->version->range('1');
$story->gen(1);
$bug = zenData('bug');
$bug->project->range('0');
$bug->product->range('1');
$bug->module->range('0');
$bug->execution->range('0');
$bug->openedBuild->range('trunk');
$bug->gen(3);

$bugs = array();
$bugs[0] = array('title' => 'bug' . time(), 'deadline' => '2028-06-06');
$bugs[1] = array('title' => 'bug' . time(), 'deadline' => '2027-06-06');
$bugs[2] = array('title' => 'bug' . time(), 'deadline' => '2026-06-06');

$product = array();
$product['productID'] = 1;

r($tester->batchEdit($product, $bugs)) && p('message,status') && e('批量编辑bug成功,SUCCESS'); //批量编辑bug
$tester->closeBrowser();