#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

/**

title=测试 userModel->getPairs();
cid=1
pid=1

查找带有Closed用户的外部用户键值对 >> Closed
查找带有首字母的外部用户键值对 >> O:用户1
查找不带首字母的外部用户键值对 >> 用户1
按照给定的用户名查找用户键值对，返回值中不带有Closed用户 >> 1
按照给定的用户名查找用户键值对，返回值中带有Closed用户 >> 2
使用limit参数来限制最多获取2个用户键值对 >> 2
使用limit参数来限制最多获取50个用户键值对 >> 50
使用pofirst参数来将系统中的产品经理用户放到返回值前列 >> P:测试主管58
使用pmfirst参数来将系统中的项目经理用户放到返回值前列 >> O:其他100

*/

$user = new userTest();
$accounts                   = array('tesrasd1asd#@!#$', 'ASD123中文', 'user10', 'ccsdqq@!');
$outsideUsersWithClosed     = $user->getPairsTest('outside');
$outsideUsersWithLetter     = $user->getPairsTest('outside|noclosed');
$outsideUsersNoLetter       = $user->getPairsTest('outside|noclosed|noletter');
$usersInAccountsNoclosed    = $user->getPairsTest('noempty|noclosed', '', 0, $accounts);
$usersInAccountsWithclosed  = $user->getPairsTest('noempty', '', 0, $accounts);
$limit2Users                = $user->getPairsTest('noempty|noclosed', '', 2);
$limit50Users               = $user->getPairsTest('noempty|noclosed', '', 50);
$POFirstUsers               = $user->getPairsTest('noempty|noclosed|pofirst');
$PMFirstUsers               = $user->getPairsTest('noempty|noclosed|pmfirst');
$firstPO                    = array_slice($POFirstUsers, 0, 1);
$firstPM                    = array_slice($PMFirstUsers, 0, 1);

r($outsideUsersWithClosed)           && p('closed')     && e('Closed');       //查找带有Closed用户的外部用户键值对
r($outsideUsersWithLetter)           && p('outside1')   && e('O:用户1');      //查找带有首字母的外部用户键值对
r($outsideUsersNoLetter)             && p('outside1')   && e('用户1');        //查找不带首字母的外部用户键值对
r(count($usersInAccountsNoclosed))   && p()             && e('1');            //按照给定的用户名查找用户键值对，返回值中不带有Closed用户
r(count($usersInAccountsWithclosed)) && p()             && e('2');            //按照给定的用户名查找用户键值对，返回值中带有Closed用户
r(count($limit2Users))               && p()             && e('2');            //使用limit参数来限制最多获取2个用户键值对
r(count($limit50Users))              && p()             && e('50');           //使用limit参数来限制最多获取50个用户键值对
r($firstPO)                          && p('pd58')       && e('P:测试主管58'); //使用pofirst参数来将系统中的产品经理用户放到返回值前列
r($firstPM)                          && p('outside100') && e('O:其他100');    //使用pmfirst参数来将系统中的项目经理用户放到返回值前列