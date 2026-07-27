#!/usr/bin/env php
<?php

/**

title=测试 spaceModel::getGroupMembersBySpace();
timeout=0
cid=0

- 查询所有空间的分组成员并验证结果类型 @1
- 查询有效空间ID=1的分组成员并验证结果类型 @1
- 查询无效空间ID=9999的分组成员为空 @0
- 查询空间ID=0的分组成员并验证结果类型 @1
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

r(is_array($spaceTester->getGroupMembersBySpaceTest())) && p() && e('1');             // 查询所有空间的分组成员并验证结果类型
r(is_array($spaceTester->getGroupMembersBySpaceTest(1))) && p() && e('1');            // 查询有效空间ID=1的分组成员并验证结果类型
r($spaceTester->getGroupMembersBySpaceTest(9999)) && p() && e('0');                   // 查询无效空间ID=9999的分组成员为空
r(is_array($spaceTester->getGroupMembersBySpaceTest(0))) && p() && e('1');            // 查询空间ID=0的分组成员并验证结果类型
r(is_array($spaceTester->getGroupMembersBySpaceTest(1, true))) && p() && e('1');      // 查询空间ID=1并用allVision参数查询并验证结果类型
