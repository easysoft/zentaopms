#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::getPivotVersions();
timeout=0
cid=0

- 步骤1：查询存在透视表规格的透视表ID1 - 正常情况 @2
- 步骤2：查询存在透视表规格的透视表ID2 - 正常情况 @2
- 步骤3：查询不存在透视表规格的透视表ID3 - 边界值 @0
- 步骤4：查询不存在的透视表ID9999 - 异常输入 @0
- 步骤5：查询ID为0的无效参数 - 异常输入 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. 准备测试数据 - 使用模拟数据方式
global $tester;

// 清理并插入测试数据
$tester->dao->delete()->from('zt_pivot')->exec();
$tester->dao->delete()->from('zt_pivotspec')->exec();

// 使用实际SQL插入测试数据
$tester->dao->exec("INSERT INTO `zt_pivot` (`id`, `dimension`, `group`, `name`, `deleted`) VALUES (1, 0, 'test', '测试透视表1', '0')");
$tester->dao->exec("INSERT INTO `zt_pivot` (`id`, `dimension`, `group`, `name`, `deleted`) VALUES (2, 0, 'test', '测试透视表2', '0')");
$tester->dao->exec("INSERT INTO `zt_pivot` (`id`, `dimension`, `group`, `name`, `deleted`) VALUES (3, 0, 'test', '测试透视表3', '0')");

$tester->dao->exec("INSERT INTO `zt_pivotspec` (`pivot`, `version`, `name`) VALUES (1, '1', '版本1')");
$tester->dao->exec("INSERT INTO `zt_pivotspec` (`pivot`, `version`, `name`) VALUES (1, '2', '版本2')");
$tester->dao->exec("INSERT INTO `zt_pivotspec` (`pivot`, `version`, `name`) VALUES (2, '1', '版本1')");
$tester->dao->exec("INSERT INTO `zt_pivotspec` (`pivot`, `version`, `name`) VALUES (2, '2', '版本2')");

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$pivotTest = new pivotTest();

// 5. 测试步骤执行 - 修改断言来检查数组长度或false值
r(count($pivotTest->getPivotVersionsTest(1) ?: array())) && p() && e('2'); // 步骤1：查询存在透视表规格的透视表ID1 - 正常情况
r(count($pivotTest->getPivotVersionsTest(2) ?: array())) && p() && e('2'); // 步骤2：查询存在透视表规格的透视表ID2 - 正常情况
r($pivotTest->getPivotVersionsTest(3)) && p() && e('0'); // 步骤3：查询不存在透视表规格的透视表ID3 - 边界值
r($pivotTest->getPivotVersionsTest(9999)) && p() && e('0'); // 步骤4：查询不存在的透视表ID9999 - 异常输入
r($pivotTest->getPivotVersionsTest(0)) && p() && e('0'); // 步骤5：查询ID为0的无效参数 - 异常输入