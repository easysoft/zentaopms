#!/usr/bin/env php
<?php

/**

title=测试 zanodeZen::prepareCreateSnapshotExtras();
timeout=0
cid=0

- 执行zanodeTest模块的prepareCreateSnapshotExtrasTest方法，参数是$node1
 - 属性host @1
 - 属性name @test_snapshot
 - 属性status @creating
 - 属性from @snapshot
- 执行zanodeTest模块的prepareCreateSnapshotExtrasTest方法，参数是$node2
 - 属性name @snapshot_v1
 - 属性desc @Version 1 snapshot
 - 属性osName @CentOS 7
- 执行zanodeTest模块的prepareCreateSnapshotExtrasTest方法，参数是$node1 属性name @Name validation error
- 执行zanodeTest模块的prepareCreateSnapshotExtrasTest方法，参数是$node3
 - 属性status @creating
 - 属性memory @0
 - 属性disk @0
 - 属性fileSize @0
- 执行zanodeTest模块的prepareCreateSnapshotExtrasTest方法，参数是$node1
 - 属性desc @This is a detailed snapshot with full description
 - 属性from @snapshot
 - 属性osName @Ubuntu 20.04

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zanodezen.unittest.class.php';

error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
global $app;
if(!isset($app->rawModule)) $app->rawModule = 'zanode';
if(!isset($app->rawMethod)) $app->rawMethod = 'test';

zenData('host')->gen(0);

su('admin');

$zanodeTest = new zanodeTest();

// 准备测试节点数据
$node1 = new stdClass();
$node1->id = 1;
$node1->name = 'test-node-1';
$node1->osName = 'Ubuntu 20.04';
$node1->status = 'running';

$node2 = new stdClass();
$node2->id = 2;
$node2->name = 'test-node-2';
$node2->osName = 'CentOS 7';
$node2->status = 'running';

$node3 = new stdClass();
$node3->id = 3;
$node3->name = 'test-node-3';
$node3->osName = 'Debian 11';
$node3->status = 'running';

// 测试步骤1: 正常创建快照,使用英文字母名称
$app->post = new stdClass();
$app->post->name = 'test_snapshot';
$app->post->desc = 'Test snapshot description';
r($zanodeTest->prepareCreateSnapshotExtrasTest($node1)) && p('host,name,status,from') && e('1,test_snapshot,creating,snapshot');

// 测试步骤2: 正常创建快照,名称包含字母和数字
$app->post = new stdClass();
$app->post->name = 'snapshot_v1';
$app->post->desc = 'Version 1 snapshot';
r($zanodeTest->prepareCreateSnapshotExtrasTest($node2)) && p('name,desc,osName') && e('snapshot_v1,Version 1 snapshot,CentOS 7');

// 测试步骤3: 异常场景,快照名称为纯数字
$app->post = new stdClass();
$app->post->name = '12345';
$app->post->desc = 'Numeric name test';
r($zanodeTest->prepareCreateSnapshotExtrasTest($node1)) && p('name') && e('Name validation error');

// 测试步骤4: 正常创建快照,验证所有字段初始化
$app->post = new stdClass();
$app->post->name = 'full_snapshot';
$app->post->desc = 'Full test';
r($zanodeTest->prepareCreateSnapshotExtrasTest($node3)) && p('status,memory,disk,fileSize') && e('creating,0,0,0');

// 测试步骤5: 正常创建快照,包含完整描述信息
$app->post = new stdClass();
$app->post->name = 'detailed_snapshot';
$app->post->desc = 'This is a detailed snapshot with full description';
r($zanodeTest->prepareCreateSnapshotExtrasTest($node1)) && p('desc,from,osName') && e('This is a detailed snapshot with full description,snapshot,Ubuntu 20.04');