#!/usr/bin/env php
<?php

/**

title=测试 spaceModel::getSpaceUsers();
timeout=0
cid=0

- 查询有效空间ID=1的用户列表并验证结果类型 @1
- 查询有效空间ID=1并过滤角色manager的用户并验证结果类型 @1
- 查询无效空间ID=0的用户列表为空 @0
- 查询不存在的空间ID=9999的用户列表为空 @0
- 查询有效空间ID=2的用户列表并验证结果类型 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(10);
zenData('ops_space')->gen(10);
zenData('ops_spaceuser')->gen(10);

su('admin');

$spaceTester = new spaceModelTest();

r(is_array($spaceTester->getSpaceUsersTest(1))) && p() && e('1');            // 查询有效空间ID=1的用户列表并验证结果类型
r(is_array($spaceTester->getSpaceUsersTest(1, 'manager'))) && p() && e('1'); // 查询有效空间ID=1并过滤角色manager的用户并验证结果类型
r($spaceTester->getSpaceUsersTest(0)) && p() && e('0');                      // 查询无效空间ID=0的用户列表为空
r($spaceTester->getSpaceUsersTest(9999)) && p() && e('0');                   // 查询不存在的空间ID=9999的用户列表为空
r(is_array($spaceTester->getSpaceUsersTest(2))) && p() && e('1');            // 查询有效空间ID=2的用户列表并验证结果类型
