#!/usr/bin/env php
<?php
include dirname(__FILE__, 2) . '/lib/ui/activatebug.ui.class.php';

/**

title=批量激活bug测试
timeout=0
cid=1

- 批量激活bug测试
 - 最终测试状态 @SUCCESS
 - 测试结果 @批量激活bug成功

*/
$tester = new activateBugTester();
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

$bug->assignedTo->range('admin');
$bug->status->range('closed');
$bug->resolvedBy->range('admin');
$bug->closedBy->range('admin');
$bug->gen(3);

$user = zenData('user');
$user->id->range('1-3');
$user->account->range('admin, user1, user2');
$user->password->range($config->uitest->defaultPassword)->format('md5');
$user->realname->range('admin, USER1, USER2');
$user->gen(3);

$bugs = array();
$bugs[0] = array('assignedTo' => 'admin', 'openedBuild' => array('multiPicker' => '主干'), 'comment' => '激活comment1');
$bugs[1] = array('assignedTo' => 'USER1', 'openedBuild' => array('multiPicker' => '主干'), 'comment' => '激活comment2');
$bugs[2] = array('assignedTo' => 'USER2', 'openedBuild' => array('multiPicker' => '主干'), 'comment' => '激活comment3');

$products = array();
$products['productID'] = 1;

r($tester->batchActivate($products, $bugs)) && p('status,message') && e('SUCCESS,批量激活bug成功'); //批量激活bug测试
$tester->closeBrowser();