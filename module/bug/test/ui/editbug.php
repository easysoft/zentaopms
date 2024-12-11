#!/usr/bin/env php
<?php
chdir(__DIR__);
include '../lib/confirmbug.ui.class.php';

/**

title=编辑bug测试
timeout=0
cid=1

- 验证直接编辑bug是否成功
 - 测试结果 @编辑bug成功
 - 最终测试状态 @SUCCESS
- 验证编辑bug名称是否成功
 - 测试结果 @编辑bug成功
 - 最终测试状态 @SUCCESS

*/
zenData('product')->loadYaml('product')->gen(1);
$story = zenData('story');
$story->id->setFields(array(array('range' => '2')));
$story->version->setFields(array(array('range' => '1')));
$story->gen(1);
$bug = zenData('bug');
$bug->id->setFields(array(array('range' => 1)));
$bug->title->setFields(array(array('range' => 'bug1')));
$bug->status->setFields(array(array('range' => 'active')));
$bug->module->setFields(array(array('range' => '0')));
$bug->execution->setFields(array(array('range' => '0')));
$bug->plan->setFields(array(array('range' => '0')));
$bug->story->setFields(array(array('range' => '0')));
$bug->storyVersion->setFields(array(array('range' => '0')));
$bug->resolvedBuild->setFields(array(array('range' => 'trunk')));
$bug->testtask->setFields(array(array('range' => '0')));
$bug->gen(1);
$tester = new confirmBugTester();

$bug = array();
$bug['search'][] = array('field1' => 'Bug状态', 'operator1' => '=', 'value1' => '激活');
$product = array();
$product['productID'] = 1;

r($tester->editBug($product, $bug)) && p('message,status') && e('编辑bug成功,SUCCESS'); //验证直接编辑bug是否成功

$bug['bugName']  = 'bug' . time();
r($tester->editBug($product, $bug)) && p('message,status') && e('编辑bug成功,SUCCESS'); //验证编辑bug名称是否成功
$tester->closeBrowser();