#!/usr/bin/env php
<?php

/**

title=测试 spaceModel::removeMember();
timeout=0
cid=0

- 从有效空间移除有效成员并验证返回结果 @1
- 从有效空间移除不存在的成员并验证返回结果 @1
- 从无效空间ID=0移除成员并验证返回结果 @1
- 从无效空间ID=9999移除成员并验证返回结果 @1
- 移除成员后验证返回结果无错误 @1

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

r($spaceTester->removeMemberTest(1, 'test1')) && p() && e('1');     // 从有效空间移除有效成员并验证返回结果
r($spaceTester->removeMemberTest(1, 'notexist')) && p() && e('1');  // 从有效空间移除不存在的成员并验证返回结果
r($spaceTester->removeMemberTest(0, 'test2')) && p() && e('1');     // 从无效空间ID=0移除成员并验证返回结果
r($spaceTester->removeMemberTest(9999, 'test3')) && p() && e('1');  // 从无效空间ID=9999移除成员并验证返回结果
r($spaceTester->removeMemberTest(1, 'test1')) && p() && e('1');     // 移除成员后验证返回结果无错误
