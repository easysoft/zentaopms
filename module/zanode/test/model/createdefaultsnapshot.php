#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 zanodeModel::createDefaultSnapshot();
timeout=0
cid=19822

- 步骤1：测试节点1创建默认快照
 - 属性name @请检查执行节点状态
- 步骤2：测试节点4创建默认快照属性name @请检查执行节点状态
- 步骤3：测试无效节点ID（0）属性name @请检查执行节点状态
- 步骤4：测试负数节点ID属性name @请检查执行节点状态
- 步骤5：测试不存在的节点ID属性name @请检查执行节点状态

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$hostTable = zenData('host');
$hostTable->id->range('1-10');
$hostTable->name->range('node1,node2,node3,node4,node5,node6,node7,node8,node9,node10');
$hostTable->type->range('node');
$hostTable->status->range('running,running,running,ready,wait,shutoff,ready,wait,shutoff,shutoff');
$hostTable->extranet->range('127.0.0.1');
$hostTable->parent->range('0');
$hostTable->hostType->range('virtual');
$hostTable->zap->range('8081');
$hostTable->tokenSN->range('test_token');
$hostTable->gen(10);

zenData('user')->gen(5);
su('admin');

$zanode = new zanodeModelTest();

r($zanode->createDefaultSnapshotTest(1)) && p('name') && e('请检查执行节点状态,网络请求失败或Agent服务不可用'); // 步骤1：测试节点1创建默认快照
r($zanode->createDefaultSnapshotTest(4)) && p('name') && e('请检查执行节点状态'); // 步骤2：测试节点4创建默认快照
r($zanode->createDefaultSnapshotTest(0)) && p('name') && e('请检查执行节点状态'); // 步骤3：测试无效节点ID（0）
r($zanode->createDefaultSnapshotTest(-1)) && p('name') && e('请检查执行节点状态'); // 步骤4：测试负数节点ID
r($zanode->createDefaultSnapshotTest(999)) && p('name') && e('请检查执行节点状态'); // 步骤5：测试不存在的节点ID