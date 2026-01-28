#!/usr/bin/env php
<?php

/**

title=测试 userModel->getPairs();
cid=19621

- 查找带有Closed用户的外部用户键值对属性closed @Closed
- 查找带有首字母的外部用户键值对属性account4 @A:用户名4
- 查找不带首字母的外部用户键值对属性account4 @用户名4
- 按照给定的用户名查找用户键值对，返回值中不带有Closed用户 @1
- 按照给定的用户名查找用户键值对，返回值中带有Closed用户 @2
- 使用limit参数来限制最多获取2个用户键值对 @2
- 使用limit参数来限制最多获取50个用户键值对 @3
- 使用pofirst参数来将系统中的产品经理用户放到返回值前列属性account1 @A:用户名1
- 使用pmfirst参数来将系统中的项目经理内部和外部用户放到返回值前列属性account3 @A:用户名3

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$user = zenData('user');
$user->id->range('1001-1006');
$user->account->range('1-6')->prefix("account");
$user->realname->range('1-6')->prefix("用户名");
$user->type->range('inside{3},outside{3}');
$user->role->range('po,pd,qa,qd,pm,dev');
$user->deleted->range('0{4}, 1{2}');
$user->visions->range('rnd');
$user->gen(5);

$usersToAppended = array('account5', 'account1');

$user = new userModelTest();
$outsideUsersWithClosed     = $user->getPairsTest('outside');
$outsideUsersWithLetter     = $user->getPairsTest('outside|noclosed');
$outsideUsersNoLetter       = $user->getPairsTest('outside|noclosed|noletter');
$usersInAccountsNoclosed    = $user->getPairsTest('noempty|noclosed', '', 0, $usersToAppended);
$usersInAccountsWithclosed  = $user->getPairsTest('noempty', '', 0, $usersToAppended);
$limit2Users                = $user->getPairsTest('noempty|noclosed', '', 2);
$limit50Users               = $user->getPairsTest('noempty|noclosed', '', 50);
$POFirstUsers               = $user->getPairsTest('noempty|noclosed|pofirst');
$PMFirstUsers               = $user->getPairsTest('noempty|noclosed|all|devfirst');
$firstPO                    = array_slice($POFirstUsers, 0, 1);
$firstPM                    = array_slice($PMFirstUsers, 0, 1);

r($outsideUsersWithClosed)           && p('closed')     && e('Closed');       //查找带有Closed用户的外部用户键值对
r($outsideUsersWithLetter)           && p('account4')   && e('A:用户名4');    //查找带有首字母的外部用户键值对
r($outsideUsersNoLetter)             && p('account4')   && e('用户名4');      //查找不带首字母的外部用户键值对
r(count($usersInAccountsNoclosed))   && p()             && e('1');            //按照给定的用户名查找用户键值对，返回值中不带有Closed用户
r(count($usersInAccountsWithclosed)) && p()             && e('2');            //按照给定的用户名查找用户键值对，返回值中带有Closed用户
r(count($limit2Users))               && p()             && e('2');            //使用limit参数来限制最多获取2个用户键值对
r(count($limit50Users))              && p()             && e('3');            //使用limit参数来限制最多获取50个用户键值对
r($firstPO)                          && p('account1')   && e('A:用户名1');    //使用pofirst参数来将系统中的产品经理用户放到返回值前列
r($firstPM)                          && p('account3')   && e('A:用户名3');    //使用pmfirst参数来将系统中的项目经理内部和外部用户放到返回值前列
