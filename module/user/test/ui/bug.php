#!/usr/bin/env php
<?php
include dirname(__FILE__, 2) . '/lib/ui/bug.ui.class.php';

/**

title=开源版m=user&f=bug测试
timeout=0
cid=1

- 开源版m=user&f=bug测试
 - 最终测试状态 @SUCCESS
 - 测试结果 @开源版m=user&f=bug测试成功

*/

$user = zenData('user');
$user->id->range('1-3');
$user->account->range('admin,user1,user2');
$user->realname->range('管理员,用户1,用户2');
$user->password->range($config->uitest->defaultPassword)->format('md5');
$user->role->range('admin,dev,qa');
$user->gender->range('f,m');
$user->gen(3);

$product = zenData('product');
$product->id->range('1');
$product->name->range('产品1');
$product->type->range('normal');
$product->status->range('normal');
$product->gen(1);

$bug = zenData('bug');
$bug->id->range('1-18');
$bug->product->range('1');
$bug->title->range('1-18')->prefix('BUG');
$bug->severity->range('1,2,3,4');
$bug->pri->range('1,2,3,4');
$bug->type->range('codeerror,install,performance,others');
$bug->assignedTo->range('admin,user1,user2');
$bug->openedBy->range('admin,user1,user2');
$bug->resolvedBy->range('admin,user1,user2');
$bug->closedBy->range('admin,user1,user2');
$bug->resolution->range('fixed,duplicate,bydesign,');
$bug->status->range('active,resolved,closed');
$bug->gen(18);

global $uiTester;
$users = $uiTester->dao->select('*')->from('zt_user')->fetchAll();

// 补充 openedByName/resolvedByName 字段用于比对（页面使用 userMap 渲染真实姓名）
$bugs = $uiTester->dao->select("b.*, u1.realname AS openedByName, u2.realname AS resolvedByName")
    ->from('zt_bug')->alias('b')
    ->leftJoin('zt_user')->alias('u1')->on('b.openedBy = u1.account')
    ->leftJoin('zt_user')->alias('u2')->on('b.resolvedBy = u2.account')
    ->fetchAll('id');

$tester = new bugTester();

r($tester->verifyUserBugMenus($users, $bugs, 5)) && p('status,message') && e('SUCCESS,开源版m=user&f=bug测试成功'); //开源版m=user&f=bug测试

$tester->closeBrowser();