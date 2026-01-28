#!/usr/bin/env php
<?php

/**

title=测试 userModel->getList();
cid=19615

- 查找系统中第一个未删除的、内部用户真实姓名,根据account正序排第0条的realname属性 @用户名1
- 查找系统中第二个未删除的、内部用户真实姓名,根据account正序排第1条的realname属性 @用户名3
- 查找系统中所有未删除的、内部用户的数量 @2
- 查找系统中第一个用户的真实姓名第0条的realname属性 @用户名1
- 查找系统中第二个用户的真实姓名第1条的realname属性 @用户名2
- 查找系统中第三个用户的真实姓名第2条的realname属性 @用户名3
- 查找系统中第四个用户的真实姓名第3条的realname属性 @用户名4
- 查找系统中第五个用户的真实姓名第4条的realname属性 @用户名5
- 查找系统中所有用户数量 @5

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

$user = zenData('user');
$user->id->range('1001-1005');
$user->account->range('1-5')->prefix("account");
$user->realname->range('1-5')->prefix("用户名");
$user->type->range('inside{3},outside{2}');
$user->deleted->range('0-1');
$user->gen(5);

$user = new userModelTest();

r($user->getListTest())                  && p('0:realname') && e('用户名1'); // 查找系统中第一个未删除的、内部用户真实姓名,根据account正序排
r($user->getListTest())                  && p('1:realname') && e('用户名3'); // 查找系统中第二个未删除的、内部用户真实姓名,根据account正序排
r($user->getListTest('nodeleted', true)) && p()             && e('2');       // 查找系统中所有未删除的、内部用户的数量
r($user->getListTest('all'))             && p('0:realname') && e('用户名1'); // 查找系统中第一个用户的真实姓名
r($user->getListTest('all'))             && p('1:realname') && e('用户名2'); // 查找系统中第二个用户的真实姓名
r($user->getListTest('all'))             && p('2:realname') && e('用户名3'); // 查找系统中第三个用户的真实姓名
r($user->getListTest('all'))             && p('3:realname') && e('用户名4'); // 查找系统中第四个用户的真实姓名
r($user->getListTest('all'))             && p('4:realname') && e('用户名5'); // 查找系统中第五个用户的真实姓名
r($user->getListTest('all', true))       && p()             && e('5');       // 查找系统中所有用户数量
