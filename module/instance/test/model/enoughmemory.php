#!/usr/bin/env php
<?php

/**

title=测试 instanceModel::enoughMemory();
timeout=0
cid=16792

- 步骤1：正常情况-低内存需求（测试环境集群资源为0） @0
- 步骤2：高内存需求 @0
- 步骤3：边界值测试 @0
- 步骤4：零内存需求 @1
- 步骤5：负数内存需求 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$instanceTest = new instanceModelTest();

// 4. 准备测试数据 - 创建不同内存需求的应用对象
$lowMemoryApp = new stdclass;
$lowMemoryApp->memory = 512; // 512MB

$highMemoryApp = new stdclass;
$highMemoryApp->memory = 16384; // 16GB

$boundaryApp = new stdclass;
$boundaryApp->memory = 1000; // 假设为边界值

$zeroMemoryApp = new stdclass;
$zeroMemoryApp->memory = 0;

$negativeMemoryApp = new stdclass;
$negativeMemoryApp->memory = -100;

// 5. 强制要求：必须包含至少5个测试步骤
r($instanceTest->enoughMemoryTest($lowMemoryApp)) && p() && e('0');      // 步骤1：正常情况-低内存需求（测试环境集群资源为0）
r($instanceTest->enoughMemoryTest($highMemoryApp)) && p() && e('0');     // 步骤2：高内存需求
r($instanceTest->enoughMemoryTest($boundaryApp)) && p() && e('0');       // 步骤3：边界值测试
r($instanceTest->enoughMemoryTest($zeroMemoryApp)) && p() && e('1');     // 步骤4：零内存需求
r($instanceTest->enoughMemoryTest($negativeMemoryApp)) && p() && e('1'); // 步骤5：负数内存需求