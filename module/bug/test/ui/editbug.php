#!/usr/bin/env php
<?php
chdir(__DIR__);
include '../lib/ui/confirmbug.ui.class.php';

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
$story->id->range('2');
$story->version->range('1');
$story->gen(1);
zenData('project')->loadYaml('execution')->gen(1);
$bug = zenData('bug');
$bug->project->range('0');
$bug->product->range('1');
$bug->module->range('0');
$bug->execution->range('0');
$bug->openedBuild->range('trunk');
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