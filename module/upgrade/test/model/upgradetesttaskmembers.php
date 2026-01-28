#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->upgradeTesttaskMembers();
cid=19565

- 判断测试任务成员是否更新成功且数据正确。
 - 第0条的members属性 @user1,user2,user3,user4
 - 第1条的members属性 @user5,user6,user7,user8
 - 第2条的members属性 @user9,user10

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('testrun')->loadYaml('user_testrun')->gen(10);
zenData('user')->gen(10);
zenData('testtask')->gen(3);

$upgrade = new upgradeModelTest();

$upgrade->upgradeTesttaskMembers();

global $tester;

$info = $tester->dao->select('*')->from('zt_testtask')->where('id')->in('1,2,3')->fetchAll();

r($info) && p('0:members;1:members;2:members', ';') && e('user1,user2,user3,user4;user5,user6,user7,user8;user9,user10');  //判断测试任务成员是否更新成功且数据正确。
