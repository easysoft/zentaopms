#!/usr/bin/env php
<?php

/**

title=测试 qaModel::setMenu();
timeout=0
cid=17979

- 执行qaTest模块的setMenuTest方法  @1
- 执行qaTest模块的setMenuTest方法，参数是1  @1
- 执行qaTest模块的setMenuTest方法，参数是2, 'branch1'  @1
- 执行qaTest模块的setMenuTest方法，参数是999  @1
- 执行qaTest模块的setMenuTest方法，参数是-1  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$product = zenData('product');
$product->id->range('1-10');
$product->name->range('产品1,产品2,产品3{5},产品4{3}');
$product->type->range('normal{8},branch{2}');
$product->status->range('normal{9},closed{1}');
$product->acl->range('open{5},private{3},custom{2}');
$product->gen(10);

$user = zenData('user');
$user->id->range('1-5');
$user->account->range('admin,user1,user2,user3,user4');
$user->password->range('123456{5}');
$user->role->range('admin,limited{4}');
$user->realname->range('管理员,用户1,用户2,用户3,用户4');
$user->gen(5);

$userview = zenData('userview');
$userview->account->range('admin,user1,user2,user3,user4');
$userview->products->range('1,2,3,4,5,6,7,8,9,10;1,2,3;4,5,6;1,2,3;7,8,9');
$userview->gen(5);

unset($_SESSION['tutorialMode']);

su('admin');

$qaTest = new qaModelTest();

r($qaTest->setMenuTest(0)) && p() && e('1');
r($qaTest->setMenuTest(1)) && p() && e('1');
r($qaTest->setMenuTest(2, 'branch1')) && p() && e('1');
r($qaTest->setMenuTest(999)) && p() && e('1');
r($qaTest->setMenuTest(-1)) && p() && e('1');