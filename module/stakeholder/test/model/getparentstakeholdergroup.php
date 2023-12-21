#!/usr/bin/env php
<?php
/**

title=测试 stakeholderModel->getParentStakeholderGroup();
cid=1

- 获取对象ID为0的父对象干系人分组信息 @0
- 获取项目集ID为1-10的父项目集干系人分组信息第2条的admin属性 @admin
- 获取项目ID为11的所属项目集干系人分组信息第11条的admin属性 @admin
- 获取项目ID不存在的所属项目干系人分组信息 @0
- 获取项目集ID为1-10的父项目集干系人分组信息数量 @10
- 获取项目ID为11的所属项目集干系人分组信息数量 @10

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/stakeholder.class.php';

$projectTable = zdTable('project')->config('project');
$projectTable->acl->range('private');
$projectTable->gen(15);

zdTable('stakeholder')->config('stakeholder')->gen(20);
zdTable('user')->gen(5);

$objectIds[0] = array();
$objectIds[1] = range(1, 10);
$objectIds[2] = array(11);
$objectIds[3] = array(200, 201, 202);

$stakeholderTester = new stakeholderTest();
r($stakeholderTester->getParentStakeholderGroupTest($objectIds[0])) && p()           && e('0');     // 获取对象ID为0的父对象干系人分组信息
r($stakeholderTester->getParentStakeholderGroupTest($objectIds[1])) && p('2:admin')  && e('admin'); // 获取项目集ID为1-10的父项目集干系人分组信息
r($stakeholderTester->getParentStakeholderGroupTest($objectIds[2])) && p('11:admin') && e('admin'); // 获取项目ID为11的所属项目集干系人分组信息
r($stakeholderTester->getParentStakeholderGroupTest($objectIds[3])) && p()           && e('0');     // 获取项目ID不存在的所属项目干系人分组信息

r(count($stakeholderTester->getParentStakeholderGroupTest($objectIds[1])[2]))  && p() && e('10'); // 获取项目集ID为1-10的父项目集干系人分组信息数量
r(count($stakeholderTester->getParentStakeholderGroupTest($objectIds[2])[11])) && p() && e('10'); // 获取项目ID为11的所属项目集干系人分组信息数量
