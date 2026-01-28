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

$userTable = zenData('user');
$userTable->id->range('1-5');
$userTable->account->range('admin,user1,user2,manager,developer');
$userTable->realname->range('管理员,用户1,用户2,经理,开发者');
$userTable->password->range('123456{5}');
$userTable->deleted->range('0{5}');
$userTable->gen(5);

$companyTable = zenData('company');
$companyTable->id->range('1');
$companyTable->name->range('测试公司');
$companyTable->admins->range('admin,user1,user2');
$companyTable->gen(1);

su('user1');

$userTest = new userModelTest();

r($userTest->suTest()) && p('result') && e('1');

r($userTest->suTest()) && p('currentUser:account') && e('admin');

$userTest->objectModel->dao->update(TABLE_COMPANY)->set('admins')->eq('manager,developer,user1')->exec();
r($userTest->suTest()) && p('currentUser:account') && e('manager');

$userTest->objectModel->dao->update(TABLE_COMPANY)->set('admins')->eq(',admin,user1,')->exec();
r($userTest->suTest()) && p('currentUser:account') && e('admin');

$userTest->objectModel->dao->update(TABLE_COMPANY)->set('admins')->eq('')->exec();
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
