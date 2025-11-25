#!/usr/bin/env php
<?php

/**

title=测试 blockZen::getAvailableCodes();
timeout=0
cid=15239

- 步骤1：正常product模块属性overview @产品总览
- 步骤2：正常project模块属性overview @项目总览
- 步骤3：空字符串模块 @0
- 步骤4：不存在模块 @0
- 步骤5：qa模块属性statistic @产品的测试统计

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$blockTest = new blockTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($blockTest->getAvailableCodesTest('product')) && p('overview') && e('产品总览'); // 步骤1：正常product模块
r($blockTest->getAvailableCodesTest('project')) && p('overview') && e('项目总览'); // 步骤2：正常project模块
r($blockTest->getAvailableCodesTest('')) && p() && e('0'); // 步骤3：空字符串模块
r($blockTest->getAvailableCodesTest('nonexistent')) && p() && e('0'); // 步骤4：不存在模块
r($blockTest->getAvailableCodesTest('qa')) && p('statistic') && e('产品的测试统计'); // 步骤5：qa模块