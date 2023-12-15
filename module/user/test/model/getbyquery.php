#!/usr/bin/env php
<?php
/**
title=测试 userModel::getByQuery();
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';

$table = zdTable('user');
$table->type->range('outside{2},inside{8}');
$table->role->range('dev,qa,pm,po,td,pd,qd,top,others');
$table->deleted->range('1,0{9}');
$table->gen(10);

su('admin');

global $app;
$app->loadClass('pager', true);
$app->setModuleName('user');
$app->setMethodName('browse');
$pager = new pager(0, 5, 1);

$userTest = new userTest();

$users = $userTest->getByQueryTest();
r(count($users)) && p() && e(8); // 使用默认参数，有 8 个用户。

$users = $userTest->getByQueryTest('inside');
r(count($users)) && p() && e(8); // 不分页查找内部用户，有 8 个用户。

$users = $userTest->getByQueryTest('outside');
r(count($users)) && p() && e(1); // 不分页查找外部用户，有 1 个用户。

$users = $userTest->getByQueryTest('all');
r(count($users)) && p() && e(9); // 不分页查找所有用户，有 9 个用户。

$users = $userTest->getByQueryTest('all', '');
r(count($users)) && p() && e(9); // 不分页查询条件为空，有 9 个用户。

$users = $userTest->getByQueryTest('all', '1 = 1');
r(count($users)) && p() && e(9); // 不分页查询条件为 1 = 1，有 9 个用户。

$users = $userTest->getByQueryTest('all', "role = 'dev'");
r(count($users)) && p() && e(1); // 不分页查找角色为 dev 的用户，有 8 个用户。

$users = $userTest->getByQueryTest('all', "type = 'inside'");
r(count($users)) && p() && e(8); // 不分页查找内部用户，有 8 个用户。

$users = $userTest->getByQueryTest('all', "type = 'inside'", $pager);
r(count($users)) && p()               && e(5);         // 分页只查找内部用户，按默认排序第 1 页有 5 个用户。
r($users)        && p('0:id,account') && e('3,user2'); // 分页只查找内部用户，按 id 正序第 1 页第 1 个用户是 user1。

$users = $userTest->getByQueryTest('all', "type = 'inside'", $pager, 'id desc');
r(count($users)) && p()               && e(5);          // 分页只查找内部用户，按 id 倒序第 1 页有 5 个用户。
r($users)        && p('0:id,account') && e('10,user9'); // 分页只查找内部用户，按 id 倒序第 1 页第 1 个用户是 user9。

$pager = new pager(0, 5, 2);
$users = $userTest->getByQueryTest('all', "type = 'inside'", $pager);
r(count($users)) && p()               && e(3);         // 分页只查找内部用户，按默认排序第 2 页有 3 个用户。
r($users)        && p('0:id,account') && e('8,user7'); // 分页只查找内部用户，按默认排序第 2 页第 1 个用户是 user6。

$users = $userTest->getByQueryTest('all', '', $pager);
r(count($users)) && p()               && e(4);         // 分页按默认排序第 2 页有 4 个用户。
r($users)        && p('0:id,account') && e('7,user6'); // 分页按默认排序第 2 页第 1 个用户是 user6。

$users = $userTest->getByQueryTest('all', '', $pager, 'id');
r(count($users)) && p()               && e(4);         // 分页按 id 正序第 2 页有 4 个用户。
r($users)        && p('0:id,account') && e('7,user6'); // 分页按 id 正序第 2 页第 1 个用户是 user6。

$users = $userTest->getByQueryTest('all', '', $pager, 'id desc');
r(count($users)) && p()               && e(4);         // 分页按 id 倒序第 2 页有 4 个用户。
r($users)        && p('0:id,account') && e('5,user4'); // 分页按 id 倒序第 2 页第 1 个用户是 user4。
