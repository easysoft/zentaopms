#!/usr/bin/env php
<?php
include dirname(__FILE__, 2) . '/lib/ui/activatebug.ui.class.php';

/**

title=激活bug测试
timeout=0
cid=1

- 激活bug测试
 - 最终测试状态 @SUCCESS
 - 测试结果 @激活bug成功

*/

zenData('product')->loadYaml('product')->gen(1);
$bug = zenData('bug');
$bug->project->range('0');
$bug->product->range('1');
$bug->module->range('0');
$bug->execution->range('0');
$bug->openedBuild->range('trunk');
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

$product = array();
$product['productID'] = 1;

$bugs = zenData('bug')->dao->select('id, title')->from(TABLE_BUG)->fetchAll();

$tester = new activateBugTester();
r($tester->activateBug($product, $bugs[0], 'USER1')) && p('status,message') && e('SUCCESS,激活bug成功'); //激活bug测试
$tester->closeBrowser();