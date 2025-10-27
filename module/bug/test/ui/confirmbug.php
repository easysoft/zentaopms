#!/usr/bin/env php
<?php
chdir(__DIR__);
include '../lib/ui/confirmbug.ui.class.php';

/**

title=确认bug
timeout=0
cid=1

- 验证确认bug测试结果 @确认bug成功
最终测试状态 @确认bug成功

*/

zenData('product')->loadYaml('product')->gen(1);
$story = zenData('story');
$story->id->setFields(array(array('range' => '2')));
$story->version->setFields(array(array('range' => '1')));
$story->gen(1);
$bug = zenData('bug');
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
$bug['search'][0]  = array('field1' => '是否确认', 'operator1' => '=', 'value1' => '未确认');
$bug['assignedTo'] = 'admin';
$bug['type']       = '其他';
$bug['pri']        = '2';
$bug['deadline']   = '2027-02-15';
$bug['mailto']     = array('multiPicker' => 'admin');
$product = array();
$product['productID'] = 1;

r($tester->confirmBug($product, $bug)) && p('message,status') && e('确认bug成功'); //验证确认bug