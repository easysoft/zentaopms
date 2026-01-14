#!/usr/bin/env php
<?php

/**

title=测试 commonModel::checkUpgradeStatus();
timeout=0
cid=15664

- 步骤1：测试checkUpgradeStatus正常调用情况 @1
- 步骤2：测试容器环境下的行为 @1
- 步骤3：测试有安全文件的情况 @1
- 步骤4：测试升级过程中的行为 @1
- 步骤5：测试输出缓冲区处理 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
try {
    su('admin');
} catch (Exception $e) {
    // 在某些测试环境中，用户登录可能失败，我们继续执行测试
}

// 3. 创建测试实例（变量名与模块名一致）
$commonTest = new commonModelTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($commonTest->checkUpgradeStatusTest()) && p() && e('1');                            // 步骤1：测试checkUpgradeStatus正常调用情况
r($commonTest->checkUpgradeStatusTest('container')) && p() && e('1');                 // 步骤2：测试容器环境下的行为
r($commonTest->checkUpgradeStatusTest('safefile')) && p() && e('1');                 // 步骤3：测试有安全文件的情况
r($commonTest->checkUpgradeStatusTest('upgrading')) && p() && e('1');                // 步骤4：测试升级过程中的行为
r($commonTest->checkUpgradeStatusTest('outputbuffer')) && p() && e('1');             // 步骤5：测试输出缓冲区处理