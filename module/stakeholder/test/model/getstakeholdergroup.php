#!/usr/bin/env php
<?php
/**

title=测试 stakeholderModel->getStakeholderGroup();
cid=1

- 测试传入空的对象ID列表 @0
- 测试传入对象ID列表第1条的admin属性 @admin
- 测试传入不存在的对象ID列表 @0
- 测试传入对象ID列表获取的干系人数量 @10
- 测试传入对象ID列表获取的干系人数量 @10

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/stakeholder.class.php';

zdTable('stakeholder')->config('stakeholder')->gen(20);
zdTable('user')->gen(5);

$objectIds[0] = array();
$objectIds[1] = array(1, 11);
$objectIds[2] = range(16, 20);

$stakeholderTester = new stakeholderTest();
r($stakeholderTester->getStakeholderGroupTest($objectIds[0])) && p()          && e('0');     // 测试传入空的对象ID列表
r($stakeholderTester->getStakeholderGroupTest($objectIds[1])) && p('1:admin') && e('admin'); // 测试传入对象ID列表
r($stakeholderTester->getStakeholderGroupTest($objectIds[2])) && p()          && e('0');     // 测试传入不存在的对象ID列表

r(count($stakeholderTester->getStakeholderGroupTest($objectIds[1])[1]))  && p() && e('10'); // 测试传入对象ID列表获取的干系人数量
r(count($stakeholderTester->getStakeholderGroupTest($objectIds[1])[11])) && p() && e('10'); // 测试传入对象ID列表获取的干系人数量
