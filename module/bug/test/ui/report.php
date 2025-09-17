#!/usr/bin/env php
<?php
chdir(__DIR__);
include '../lib/ui/confirmbug.ui.class.php';

/**

title=bug表单测试
timeout=0
cid=1

- 验证bug表单
 - 测试结果 @bug表单验证成功
 - 最终测试状态 @SUCCESS

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
$bug['search'][0]     = array('field1' => 'Bug状态', 'operator1' => '=', 'value1' => '激活');
$bug['isResolved']    = '激活';
$bug['assignedTo']    = 'admin';
$bug['resolution']    = '已解决';
$bug['resolvedBuild'] = '主干';
$bug['resolvedDate']  = '2027-02-15 09:42';
$product = array();
$product['productID']  = 1;
$product['browseType'] = 'unclosed';
$product['branch']     = 'all';
$product['module']     = 0;

r($tester->report($product, $bug)) && p('message,status') && e('bug表单验证成功,SUCCESS'); //验证bug表单
$tester->closeBrowser();