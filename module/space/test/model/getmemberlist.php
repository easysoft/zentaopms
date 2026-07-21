#!/usr/bin/env php
<?php

/**

title=测试 spaceModel::getMemberList();
timeout=0
cid=0

- 获取所有空间成员列表并验证结果类型 @1
- 获取空间成员并验证空间ID 1的成员数量大于0 @1
- 获取空间成员并验证admin成员存在 @1
- 获取空间成员并验证返回为数组 @1
- 验证成员数据不为空 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(10);
zenData('ops_space')->gen(10);
zenData('ops_spaceuser')->gen(10);
zenData('ops_repouser')->gen(5);

su('admin');

$spaceTester = new spaceModelTest();

$members = $spaceTester->getMemberListTest();

r(is_array($members)) && p() && e('1');             // 获取所有空间成员列表并验证结果类型
r(!empty($members[1])) && p() && e('1');            // 获取空间成员并验证空间ID 1的成员数量大于0
r(!empty($members[1]['admin'])) && p() && e('1');   // 获取空间成员并验证admin成员存在
r(is_array($members)) && p() && e('1');             // 获取空间成员并验证返回为数组
r(!empty($members)) && p() && e('1');               // 验证成员数据不为空
