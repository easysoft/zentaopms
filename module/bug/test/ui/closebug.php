#!/usr/bin/env php
<?php
chdir(__DIR__);
include '../lib/ui/confirmbug.ui.class.php';

/**

title=关闭bug
timeout=0
cid=1

- 验证关闭bug测试结果 @关闭bug成功
最终测试状态 @关闭bug成功

*/

zenData('product')->loadYaml('product')->gen(1);
$story = zenData('story');
$story->id->setFields(array(array('range' => '2')));
$story->version->setFields(array(array('range' => '1')));
$story->gen(1);
$bug = zenData('bug');
$bug->status->setFields(array(array('range' => 'resolved')));
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
$bug['comment'] = '关闭bug备注';

$product = array();
$product['productID'] = 1;

r($tester->closeBug($product, $bug)) && p('message,status') && e('关闭bug成功'); //验证关闭bug