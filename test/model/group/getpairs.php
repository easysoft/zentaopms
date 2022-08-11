#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/group.class.php';
su('admin');

/**

title=测试 groupModel->getPairs();
cid=1
pid=1

测试查询产品 1   的权限分组 2 的信息 >> 研发
测试查询产品 194 的权限分组 3 的信息 >> 测试
测试查询产品 284 的权限分组 4 的信息 >> 项目经理
测试查询产品 346 的权限分组 5 的信息 >> 产品经理
测试查询产品 493 的权限分组 6 的信息 >> 研发主管
测试查询产品 519 的权限分组 7 的信息 >> 产品主管

*/

$group = new groupTest();

r($group->getPairsTest(1))   && p('2') && e('研发');     // 测试查询产品 1   的权限分组 2 的信息
r($group->getPairsTest(194)) && p('3') && e('测试');     // 测试查询产品 194 的权限分组 3 的信息
r($group->getPairsTest(284)) && p('4') && e('项目经理'); // 测试查询产品 284 的权限分组 4 的信息
r($group->getPairsTest(346)) && p('5') && e('产品经理'); // 测试查询产品 346 的权限分组 5 的信息
r($group->getPairsTest(493)) && p('6') && e('研发主管'); // 测试查询产品 493 的权限分组 6 的信息
r($group->getPairsTest(519)) && p('7') && e('产品主管'); // 测试查询产品 519 的权限分组 7 的信息