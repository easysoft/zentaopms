#!/usr/bin/env php
<?php
chdir(__DIR__);
include '../lib/ui/createbug.ui.class.php';

/**

title=bug列表页检查测试
timeout=0
cid=1

- bug列表页检查
 - 测试结果 @bug列表页检查成功
 - 最终测试状态 @SUCCESS

*/
$tester = new createBugTester();
zenData('product')->loadYaml('product')->gen(1);
$story = zenData('story');
$story->id->setFields(array(array('range' => '2')));
$story->version->setFields(array(array('range' => '1')));
$story->gen(1);
$bug = zenData('bug');
$bug->project->range('0');
$bug->product->range('1');
$bug->module->range('0');
$bug->execution->range('0');
$bug->openedBuild->range('trunk');
$bug->gen(3);

$product = array();
$product['productID'] = 1;

$bugs = zenData('bug')->dao->select('id, title')->from(TABLE_BUG)->fetchAll();

r($tester->browse($product, $bugs)) && p('message,status') && e('bug列表页检查成功,SUCCESS'); //bug列表页检查
$tester->closeBrowser();