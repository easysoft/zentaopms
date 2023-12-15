#!/usr/bin/env php
<?php
/**
title=测试 userModel->getListByIdList();
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('user')->config('user')->gen(2);

su('admin');

global $tester;
$userModel = $tester->loadModel('user');

$users = $userModel->getListByIdList(array());
r(count($users)) && p() && e(0); // 参数为空数组，返回空数组。

$users = $userModel->getListByIdList(array(0));
r(count($users)) && p() && e(0); // id 为 0 的用户不存在，返回空数组。

$users = $userModel->getListByIdList(array(3));
r(count($users)) && p() && e(0); // id 为 4 的用户不存在，返回空数组。

$users = array_values($userModel->getListByIdList(array(1)));
r(count($users)) && p()       && e(1); // id 为 1 的用户存在，返回包含 1 个用户的数组。
r($users)        && p('0:id') && e(1); // 返回数组第 1 个用户的 id 应该是 1。

$users = array_values($userModel->getListByIdList(array(2, 1)));
r(count($users)) && p()       && e(2); // id 为 2 和 1 的用户存在，返回包含 2 个用户的数组。
r($users)        && p('0:id') && e(1); // 返回数组第 1 个用户的 id 应该是 1。
r($users)        && p('1:id') && e(2); // 返回数组第 2 个用户的 id 应该是 2。

$users = array_values($userModel->getListByIdList(array(4, 3, 2, 1)));
r(count($users)) && p()       && e(2); // id 为 2 和 1 的用户存在，id 为 4 和 3 的用户不存在，返回包含 2 个用户的数组。
r($users)        && p('0:id') && e(1); // 返回数组第 1 个用户的 id 应该是 1。
r($users)        && p('1:id') && e(2); // 返回数组第 2 个用户的 id 应该是 2。
