#!/usr/bin/env php
<?php

/**

title=测试 metricZen::prepareScopeList();
timeout=0
cid=17202

- 步骤1：正常情况下返回完整的范围列表数组，验证数组长度 @7
- 步骤2：验证第一个项目范围的键 @project
- 步骤3：验证第一个项目范围的文本内容 @项目
- 步骤4：验证第二个产品范围的键 @product
- 步骤5：验证第五个系统范围的键 @system

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metriczen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
// prepareScopeList方法不依赖数据库，无需准备测试数据

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$metricZenTest = new metricZenTest();

// 获取测试结果
$result = $metricZenTest->prepareScopeListZenTest();

// 5. 强制要求：必须包含至少5个测试步骤
r(count($result)) && p() && e('7'); // 步骤1：正常情况下返回完整的范围列表数组，验证数组长度
r($result[0]['key']) && p() && e('project'); // 步骤2：验证第一个项目范围的键
r($result[0]['text']) && p() && e('项目'); // 步骤3：验证第一个项目范围的文本内容
r($result[1]['key']) && p() && e('product'); // 步骤4：验证第二个产品范围的键
r($result[4]['key']) && p() && e('system'); // 步骤5：验证第五个系统范围的键