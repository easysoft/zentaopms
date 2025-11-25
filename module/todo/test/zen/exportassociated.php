#!/usr/bin/env php
<?php

/**

title=测试 todoZen::exportAssociated();
timeout=0
cid=19298

- 步骤1：测试max版本返回3个数组 @3
- 步骤2：测试qcVersion版本属性1 @审核1
- 步骤3：测试默认版本返回7个数组 @7
- 步骤4：测试其他版本类型返回7个数组 @7
- 步骤5：测试空账号参数返回3个数组 @3

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todozen.unittest.class.php';

// 2. zendata数据准备（不需要数据库数据，因为是模拟方法）

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$todoTest = new todoTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($todoTest->exportAssociatedTest('max', 'admin')) && p() && e(3); // 步骤1：测试max版本返回3个数组
r($todoTest->exportAssociatedTest('qcVersion', 'admin')) && p('1') && e('审核1'); // 步骤2：测试qcVersion版本
r($todoTest->exportAssociatedTest('', 'admin')) && p() && e(7); // 步骤3：测试默认版本返回7个数组
r($todoTest->exportAssociatedTest('other', 'admin')) && p() && e(7); // 步骤4：测试其他版本类型返回7个数组
r($todoTest->exportAssociatedTest('max', '')) && p() && e(3); // 步骤5：测试空账号参数返回3个数组