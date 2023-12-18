#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=测试 tutorialModel->getProductStats();
cid=1

- 测试是否能拿到 admin 的数据 id program PO createdBy reviewer
 - 第1条的id属性 @1
 - 第1条的program属性 @0
 - 第1条的PO属性 @admin
 - 第1条的createdBy属性 @admin
 - 第1条的reviewer属性 @admin
- 测试是否能拿到 admin 的数据 plans第1[plans]条的1属性 @0
- 测试是否能拿到 user1 的数据 id program PO createdBy reviewer
 - 第1条的id属性 @1
 - 第1条的program属性 @0
 - 第1条的PO属性 @user1
 - 第1条的createdBy属性 @user1
 - 第1条的reviewer属性 @user1
- 测试是否能拿到 user1 的数据 plans第1[plans]条的1属性 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tutorial.class.php';

zdTable('user')->gen(5);

$tutorial = new tutorialTest();

su('admin');
r($tutorial->getProductStatsTest()) && p('1:id,program,PO,createdBy,reviewer') && e('1,0,admin,admin,admin'); // 测试是否能拿到 admin 的数据 id program PO createdBy reviewer
r($tutorial->getProductStatsTest()) && p('1:plans')                            && e('0');                     // 测试是否能拿到 admin 的数据 plans

su('user1');
r($tutorial->getProductStatsTest()) && p('1:id,program,PO,createdBy,reviewer') && e('1,0,user1,user1,user1'); // 测试是否能拿到 user1 的数据 id program PO createdBy reviewer
r($tutorial->getProductStatsTest()) && p('1:plans')                            && e('0');                     // 测试是否能拿到 user1 的数据 plans
