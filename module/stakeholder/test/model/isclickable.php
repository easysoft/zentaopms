#!/usr/bin/env php
<?php
/**

title=测试 stakeholderModel->isClickable();
cid=1

- 测试ID为0的干系人中期望按钮是否可点击 @1
- 测试ID为0的干系人中不存在干系人是否可点击 @0
- 测试ID为1的干系人中期望按钮是否可点击 @1
- 测试ID为1的干系人中不存在干系人是否可点击 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/stakeholder.class.php';

zdTable('user')->gen(5);
zdTable('stakeholder')->gen(1);

$idList  = array(0, 1);
$actions = array('expcet', 'notExists');

$stakeholderTester = new stakeholderTest();
r($stakeholderTester->isClickableTest($idList[0], $actions[0])) && p() && e('1'); // 测试ID为0的干系人中期望按钮是否可点击
r($stakeholderTester->isClickableTest($idList[0], $actions[1])) && p() && e('0'); // 测试ID为0的干系人中不存在干系人是否可点击
r($stakeholderTester->isClickableTest($idList[1], $actions[0])) && p() && e('1'); // 测试ID为1的干系人中期望按钮是否可点击
r($stakeholderTester->isClickableTest($idList[1], $actions[1])) && p() && e('0'); // 测试ID为1的干系人中不存在干系人是否可点击
