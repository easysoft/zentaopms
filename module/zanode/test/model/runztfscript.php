#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 zanodeModel::runZTFScript();
timeout=0
cid=19805

- 测试HTTP请求失败的情况 >> 期望返回超时错误
- 测试automation配置不存在 >> 期望返回属性读取错误
- 测试执行节点状态不是running >> 期望返回超时错误
- 测试执行节点ztf端口为0 >> 期望返回超时错误
- 测试执行节点tokenSN为空 >> 期望返回超时错误

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zanode.unittest.class.php';

su('admin');

$zanodeTest = new zanodeTest();

r($zanodeTest->runZTFScriptTest(1, 100, 1001)) && p() && e('自动执行失败,请检查宿主机和执行节点状态'); // 步骤1:HTTP请求失败的情况
r($zanodeTest->runZTFScriptTest(999, 100, 1002)) && p() && e('Attempt to read property "node" on bool'); // 步骤2:automation配置不存在
r($zanodeTest->runZTFScriptTest(2, 100, 1003)) && p() && e('自动执行失败,请检查宿主机和执行节点状态'); // 步骤3:节点状态为shutoff
r($zanodeTest->runZTFScriptTest(3, 100, 1004)) && p() && e('自动执行失败,请检查宿主机和执行节点状态'); // 步骤4:节点ztf端口为0
r($zanodeTest->runZTFScriptTest(4, 100, 1005)) && p() && e('自动执行失败,请检查宿主机和执行节点状态'); // 步骤5:节点tokenSN为空
