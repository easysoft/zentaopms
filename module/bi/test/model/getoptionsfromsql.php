#!/usr/bin/env php
<?php

/**

title=测试 biModel::getOptionsFromSql();
timeout=0
cid=0

- 正常查询用户账号选项属性1 @admin
- 正常查询用户账号选项属性2 @user1
- 正常查询用户真实姓名选项属性1 @用户1
- 使用不存在的键字段返回空数组 @0
- 使用不存在的值字段返回空数组 @0
- 使用聚合函数查询属性10 @total

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

zenData('user')->gen(10);
su('admin');

global $tester;
$tester->loadModel('bi');

r($tester->bi->getOptionsFromSql('SELECT id, account FROM zt_user WHERE deleted = "0"', 'mysql', 'id', 'account')) && p('1') && e('admin'); // 正常查询用户账号选项
r($tester->bi->getOptionsFromSql('SELECT id, account FROM zt_user WHERE deleted = "0"', 'mysql', 'id', 'account')) && p('2') && e('user1'); // 正常查询用户账号选项
r($tester->bi->getOptionsFromSql('SELECT id, realname FROM zt_user WHERE deleted = "0" LIMIT 1', 'mysql', 'id', 'realname')) && p('1') && e('用户1'); // 正常查询用户真实姓名选项
r(count($tester->bi->getOptionsFromSql('SELECT id, account FROM zt_user WHERE deleted = "0"', 'mysql', 'nonexistent', 'account'))) && p('') && e('0'); // 使用不存在的键字段返回空数组
r(count($tester->bi->getOptionsFromSql('SELECT id, account FROM zt_user WHERE deleted = "0"', 'mysql', 'id', 'nonexistent'))) && p('') && e('0'); // 使用不存在的值字段返回空数组
r($tester->bi->getOptionsFromSql('SELECT count(*) as cnt, "total" as label FROM zt_user', 'mysql', 'cnt', 'label')) && p('10') && e('total'); // 使用聚合函数查询