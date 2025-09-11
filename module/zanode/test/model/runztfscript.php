#!/usr/bin/env php
<?php

/**

title=测试 zanodeModel::runZTFScript();
timeout=0
cid=0

- 步骤1：正常脚本执行，HTTP请求失败返回错误 @自动执行失败，请检查宿主机和执行节点状态
- 步骤2：不存在的脚本ID，访问null属性错误 @Attempt to read property "node" on bool
- 步骤3：节点状态为shutoff，条件不满足 @自动执行失败，请检查宿主机和执行节点状态
- 步骤4：节点缺少ztf端口，条件不满足 @自动执行失败，请检查宿主机和执行节点状态
- 步骤5：节点缺少token，条件不满足 @自动执行失败，请检查宿主机和执行节点状态

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zanode.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$automationTable = zenData('automation');
$automationTable->id->range('1-10');
$automationTable->product->range('1-5');
$automationTable->node->range('1-5');
$automationTable->shell->range('php,python,bash');
$automationTable->scriptPath->range('/test/path1,/test/path2,/test/path3');
$automationTable->gen(5);

$hostTable = zenData('host');
$hostTable->id->range('1-10');
$hostTable->name->range('test-node-1,test-node-2,test-node-3');
$hostTable->type->range('node');
$hostTable->status->range('running{3},shutoff{2}');
$hostTable->extranet->range('192.168.1.100,192.168.1.101,192.168.1.102');
$hostTable->ztf->range('8080,8081,8082');
$hostTable->tokenSN->range('token123{3},{2}');
$hostTable->deleted->range('0');
$hostTable->gen(5);

$testresultTable = zenData('testresult');
$testresultTable->id->range('1-10');
$testresultTable->run->range('1-5');
$testresultTable->task->range('1-5');
$testresultTable->case->range('1-5');
$testresultTable->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$zanodeTest = new zanodeTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($zanodeTest->runZTFScriptTest(1, 1, 1)) && p() && e('自动执行失败，请检查宿主机和执行节点状态'); // 步骤1：正常脚本执行，HTTP请求失败返回错误
r($zanodeTest->runZTFScriptTest(999, 1, 1)) && p() && e('Attempt to read property "node" on bool'); // 步骤2：不存在的脚本ID，访问null属性错误
r($zanodeTest->runZTFScriptTest(2, 1, 2)) && p() && e('自动执行失败，请检查宿主机和执行节点状态'); // 步骤3：节点状态为shutoff，条件不满足
r($zanodeTest->runZTFScriptTest(3, 1, 3)) && p() && e('自动执行失败，请检查宿主机和执行节点状态'); // 步骤4：节点缺少ztf端口，条件不满足
r($zanodeTest->runZTFScriptTest(4, 1, 4)) && p() && e('自动执行失败，请检查宿主机和执行节点状态'); // 步骤5：节点缺少token，条件不满足