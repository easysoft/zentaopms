#!/usr/bin/env php
<?php
include dirname(__FILE__, 2) . '/lib/ui/createbug.ui.class.php';
include dirname(__FILE__, 4) . '/user/test/lib/ui/createuser.ui.class.php';

/**

title=bug指派测试
timeout=0
cid=1

- bug批量指派成功
 - 最终测试状态 @SUCCESS
 - 测试结果 @bug批量指派成功
- bug选择指派成功
 - 最终测试状态 @SUCCESS
 - 测试结果 @bug选择指派成功
- bug直接修改指派成功
 - 最终测试状态 @SUCCESS
 - 测试结果 @bug直接修改指派成功

*/
zenData('product')->loadYaml('product')->gen(1);

$bug = zenData('bug');
$bug->project->range('0');
$bug->product->range('1');
$bug->module->range('0');
$bug->execution->range('0');
$bug->openedBuild->range('trunk');
$bug->assignedTo->range('admin');
$bug->gen(3);

$products = zenData('product')->dao->select('id')->from(TABLE_PRODUCT)->fetchAll();
$product = array();
$product['productID'] = $products[0]->id;

$user = zenData('user');
$user->id->range('1-3');
$user->account->range('admin, user1, user2');
$user->password->range($config->uitest->defaultPassword)->format('md5');
$user->realname->range('admin, USER1, USER2');
$user->gen(3);

$tester = new createBugTester();
r($tester->batchAssign($product, 'USER1'))          && p('status,message') && e('SUCCESS,bug批量指派成功');     //bug批量指派成功
r($tester->selectAssign($product, 'BUG1', 'admin')) && p('status,message') && e('SUCCESS,bug选择指派成功');     //bug选择指派成功
r($tester->directAssign('USER2'))                   && p('status,message') && e('SUCCESS,bug直接修改指派成功'); //bug直接修改指派成功
$tester->closeBrowser();
