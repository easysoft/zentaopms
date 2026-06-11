#!/usr/bin/env php
<?php

/**

title=测试 userModel::su();
timeout=0
cid=19656

- 执行userTest模块的suTest方法 属性result @1
- 执行userTest模块的suTest方法 第currentUser条的account属性 @admin
- 执行userTest模块的suTest方法 第currentUser条的account属性 @manager
- 执行userTest模块的suTest方法 第currentUser条的account属性 @admin
- 执行$error属性message @No admin users.

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $tester;
$tester->dao->delete()->from(TABLE_USER)->exec();
$tester->dao->delete()->from(TABLE_COMPANY)->exec();
$tester->dao->exec('ALTER TABLE ' . TABLE_USER . ' AUTO_INCREMENT = 1');
$tester->dao->exec('ALTER TABLE ' . TABLE_COMPANY . ' AUTO_INCREMENT = 1');

zenData('user')->gen(5);

$userIDList = array_values($tester->dao->select('id')->from(TABLE_USER)->orderBy('id')->fetchPairs('id', 'id'));
$users = array(
    array('account' => 'admin',     'realname' => '管理员', 'role' => 'qa'),
    array('account' => 'user1',     'realname' => '用户1',  'role' => 'dev'),
    array('account' => 'user2',     'realname' => '用户2',  'role' => 'dev'),
    array('account' => 'manager',   'realname' => '经理',   'role' => 'pm'),
    array('account' => 'developer', 'realname' => '开发者', 'role' => 'dev'),
);

foreach($users as $index => $user)
{
    $tester->dao->update(TABLE_USER)->set('account')->eq($user['account'])->set('realname')->eq($user['realname'])->set('password')->eq(md5('123456'))->set('role')->eq($user['role'])->set('deleted')->eq('0')->where('id')->eq($userIDList[$index])->exec();
}

$companyTable = zenData('company');
$companyTable->name->range('测试公司');
$companyTable->admins->range('admin,user1,user2');
$companyTable->gen(1);
$companyID = $tester->dao->select('id')->from(TABLE_COMPANY)->orderBy('id')->fetch('id');
$tester->dao->update(TABLE_COMPANY)->set('admins')->eq('admin,user1,user2')->where('id')->eq($companyID)->exec();

global $app;
$app->company = $tester->dao->select('*')->from(TABLE_COMPANY)->where('id')->eq($companyID)->fetch();

su('user1');

$userTest = new userModelTest();

r($userTest->suTest()) && p('result') && e('1');
r($userTest->suTest()) && p('currentUser:account') && e('admin');

$tester->dao->update(TABLE_COMPANY)->set('admins')->eq('manager,developer,user1')->where('id')->eq($companyID)->exec();
r($userTest->suTest()) && p('currentUser:account') && e('manager');

$tester->dao->update(TABLE_COMPANY)->set('admins')->eq(',admin,user1,')->where('id')->eq($companyID)->exec();
r($userTest->suTest()) && p('currentUser:account') && e('admin');

$tester->dao->update(TABLE_COMPANY)->set('admins')->eq('')->where('id')->eq($companyID)->exec();
try
{
    $userTest->suTest();
    $error = new stdClass();
    $error->message = 'Test failed';
}
catch(EndResponseException $e)
{
    $error = new stdClass();
    $error->message = $e->getContent();
}
r($error) && p('message') && e('No admin users.');
