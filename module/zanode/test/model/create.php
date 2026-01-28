#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 zanodeModel::create();
timeout=0
cid=19821

- 执行zanodeTest模块的createTest方法，参数是$normalData
 - 属性name @test-node-1
 - 属性type @node
 - 属性status @running
- 执行zanodeTest模块的createTest方法，参数是$minimalData
 - 属性name @minimal-node
 - 属性type @node
 - 属性status @created
- 执行zanodeTest模块的createTest方法，参数是$completeData
 - 属性name @complete-node
 - 属性hostType @physics
 - 属性cpuCores @8
 - 属性memory @16
- 执行zanodeTest模块的createTest方法，参数是$filteredData
 - 属性name @filtered-node
 - 属性osName @Ubuntu 22.04
- 执行zanodeTest模块的createTestWithAction方法，参数是$actionData 属性hasAction @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 准备测试数据 - 直接使用简化的host表结构
zenData('host')->gen(0);
zenData('action')->gen(0);
zenData('user')->gen(5);
su('admin');

$zanodeTest = new zanodeModelTest();

// 测试步骤1：正常创建执行节点
$normalData = new stdclass();
$normalData->name = 'test-node-1';
$normalData->type = 'node';
$normalData->status = 'running';
r($zanodeTest->createTest($normalData)) && p('name,type,status') && e('test-node-1,node,running');

// 测试步骤2：创建具有最小必填字段的节点
$minimalData = new stdclass();
$minimalData->name = 'minimal-node';
$minimalData->type = 'node';
$minimalData->status = 'created';
r($zanodeTest->createTest($minimalData)) && p('name,type,status') && e('minimal-node,node,created');

// 测试步骤3：创建包含完整字段的节点
$completeData = new stdclass();
$completeData->hostType = 'physics';
$completeData->parent = 0;
$completeData->name = 'complete-node';
$completeData->extranet = '192.168.1.100';
$completeData->image = 2;
$completeData->cpuCores = 8;
$completeData->memory = 16;
$completeData->diskSize = 100;
$completeData->osName = 'CentOS 7';
$completeData->desc = '包含完整字段的测试节点';
$completeData->type = 'node';
$completeData->status = 'ready';
r($zanodeTest->createTest($completeData)) && p('name,hostType,cpuCores,memory') && e('complete-node,physics,8,16');

// 测试步骤4：创建包含过滤字段的节点（osNamePhysics和osNamePre应被过滤）
$filteredData = new stdclass();
$filteredData->name = 'filtered-node';
$filteredData->type = 'node';
$filteredData->status = 'running';
$filteredData->osNamePhysics = 'should-be-filtered';
$filteredData->osNamePre = 'should-be-filtered';
$filteredData->osName = 'Ubuntu 22.04';
r($zanodeTest->createTest($filteredData)) && p('name,osName') && e('filtered-node,Ubuntu 22.04');

// 测试步骤5：验证创建节点时自动生成Action记录
$actionData = new stdclass();
$actionData->name = 'action-test-node';
$actionData->type = 'node';
$actionData->status = 'launch';
r($zanodeTest->createTestWithAction($actionData)) && p('hasAction') && e('1');