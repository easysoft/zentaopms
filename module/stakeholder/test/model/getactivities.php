#!/usr/bin/env php
<?php
/**

title=测试 stakeholderModel->getActivities();
cid=1

- 获取活动 id=>name 的键值对属性1 @这是活动名称1
- 获取活动 id=>name 的键值对的数量 @20

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/stakeholder.class.php';

zdTable('activity')->gen(20);
zdTable('user')->gen(5);

$stakeholderTester = new stakeholderTest();
r($stakeholderTester->getActivitiesTest())        && p('1') && e('这是活动名称1'); // 获取活动 id=>name 的键值对
r(count($stakeholderTester->getActivitiesTest())) && p()    && e('20');            // 获取活动 id=>name 的键值对的数量
