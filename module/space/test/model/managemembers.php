#!/usr/bin/env php
<?php

/**

title=测试 spaceModel::manageMembers();
timeout=0
cid=0

- 用空成员数据管理有效空间并验证返回结果 @1
- 向有效空间添加新成员并验证返回结果 @1
- 用空成员数据管理无效空间并验证返回结果 @1
- 用空成员数据管理空间ID=0并验证返回结果 @1
- 管理成员后验证返回结果无错误 @1

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

r($spaceTester->manageMembersTest(1, array())) && p() && e('1');     // 用空成员数据管理有效空间并验证返回结果

$members = array('space' => array('newuser1', 'newuser2'));
r($spaceTester->manageMembersTest(2, $members)) && p() && e('1');   // 向有效空间添加新成员并验证返回结果

r($spaceTester->manageMembersTest(9999, array())) && p() && e('1');  // 用空成员数据管理无效空间并验证返回结果
r($spaceTester->manageMembersTest(0, array())) && p() && e('1');     // 用空成员数据管理空间ID=0并验证返回结果

global $tester;
r(!dao::isError()) && p() && e('1');                                 // 管理成员后验证返回结果无错误
