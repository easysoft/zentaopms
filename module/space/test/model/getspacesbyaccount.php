#!/usr/bin/env php
<?php

/**

title=测试 spaceModel::getSpacesByAccount();
timeout=0
cid=0

- 用户为空获取空间列表并验证结果类型 @1
- 用户为admin获取空间列表并验证结果类型 @1
- 用户为test1获取空间列表并验证结果类型 @1
- 用户不存在获取空间列表(admin用户返回全部)并验证结果类型 @1
- 验证admin用户空间数量大于0 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(10);
zenData('ops_space')->gen(10);
zenData('ops_spaceuser')->gen(10);
zenData('ops_repouser')->gen(5);

su('admin');

$spaceTester = new spaceModelTest();

r(is_array($spaceTester->getSpacesByAccountTest(''))) && p() && e('1');          // 用户为空获取空间列表并验证结果类型
r(is_array($spaceTester->getSpacesByAccountTest('admin'))) && p() && e('1');     // 用户为admin获取空间列表并验证结果类型
r(is_array($spaceTester->getSpacesByAccountTest('test1'))) && p() && e('1');     // 用户为test1获取空间列表并验证结果类型
r(is_array($spaceTester->getSpacesByAccountTest('notexist'))) && p() && e('1');  // 用户不存在获取空间列表(admin用户返回全部)并验证结果类型
r(count($spaceTester->getSpacesByAccountTest('')) > 0) && p() && e('1');         // 验证admin用户空间数量大于0
