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

zenData('user')->gen(5);

$spaceUser = zenData('ops_spaceuser');
$spaceUser->id->range('1-3');
$spaceUser->space->range('1,1,2');
$spaceUser->account->range('admin,test1,test2');
$spaceUser->role->range('manager,member,manager');
$spaceUser->gen(3);

su('admin');

$spaceTester = new spaceModelTest();

r($spaceTester->getSpaceUsersCountTest(1))                    && p() && e('2');      // 查询空间1下的用户数量
r($spaceTester->getSpaceUsersCountTest(1, 'manager'))         && p() && e('1');      // 查询空间1下经理数量
r($spaceTester->getSpaceUsersCountTest(0))                    && p() && e('0');      // 查询无效空间ID=0的用户列表为空
r($spaceTester->getSpaceUsersCountTest(9999))                 && p() && e('0');      // 查询不存在空间的用户列表为空
r($spaceTester->getSpaceUsersAccountTest(2, '', 'test2'))     && p() && e('test2');  // 查询空间2下的用户账号
