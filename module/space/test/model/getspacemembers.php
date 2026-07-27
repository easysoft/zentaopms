#!/usr/bin/env php
<?php

/**

title=测试 spaceModel::getSpaceMembers();
timeout=0
cid=0

- 查询有效空间ID=1的成员并验证结果类型 @1
- 查询有效空间ID=2的成员并验证结果类型 @1
- 查询空间ID=0的成员并验证结果类型 @1
- 查询空间ID=9999的成员为空并验证结果类型 @1
- 查询空间ID=1并用allVision参数查询并验证结果类型 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(10);
zenData('ops_space')->gen(10);
zenData('ops_spaceuser')->gen(10);
zenData('group')->gen(5);
zenData('usergroup')->gen(10);

su('admin');

$spaceTester = new spaceModelTest();

r(is_array($spaceTester->getSpaceMembersTest(1))) && p() && e('1');          // 查询有效空间ID=1的成员并验证结果类型
r(is_array($spaceTester->getSpaceMembersTest(2))) && p() && e('1');          // 查询有效空间ID=2的成员并验证结果类型
r(is_array($spaceTester->getSpaceMembersTest(0))) && p() && e('1');          // 查询空间ID=0的成员并验证结果类型
r(is_array($spaceTester->getSpaceMembersTest(9999))) && p() && e('1');       // 查询空间ID=9999的成员为空并验证结果类型
r(is_array($spaceTester->getSpaceMembersTest(1, true))) && p() && e('1');    // 查询空间ID=1并用allVision参数查询并验证结果类型
