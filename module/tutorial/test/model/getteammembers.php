#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=测试 tutorialModel->getTeamMembers();
cid=1

- 测试是否能拿到 admin 数据
 - 第admin条的project属性 @1
 - 第admin条的account属性 @admin
 - 第admin条的role属性 @qa
 - 第admin条的realname属性 @admin
 - 第admin条的userID属性 @1
 - 第admin条的days属性 @10
- 测试是否能拿到 user1 数据
 - 第user1条的project属性 @1
 - 第user1条的account属性 @user1
 - 第user1条的role属性 @qa
 - 第user1条的realname属性 @用户1
 - 第user1条的userID属性 @2
 - 第user1条的days属性 @10

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tutorial.class.php';

zdTable('user')->gen(5);

$tutorial = new tutorialTest();

su('admin');
r($tutorial->getTeamMembersTest()) && p('admin:project,account,role,realname,userID,days') && e('1,admin,qa,admin,1,10'); // 测试是否能拿到 admin 数据

su('user1');
r($tutorial->getTeamMembersTest()) && p('user1:project,account,role,realname,userID,days') && e('1,user1,qa,用户1,2,10'); // 测试是否能拿到 user1 数据
