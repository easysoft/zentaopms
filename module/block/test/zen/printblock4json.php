#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printBlock4Json();
timeout=0
cid=15249

- 步骤1：验证testData属性保留第view条的testData属性 @test_value
- 步骤2：验证app属性被移除第view条的app属性 @~~
- 步骤3：验证config属性被移除第view条的config属性 @~~
- 步骤4：验证lang属性被移除第view条的lang属性 @~~
- 步骤5：验证header属性被移除第view条的header属性 @~~

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$blockTest = new blockTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($blockTest->printBlock4JsonTest()) && p('view:testData') && e('test_value');                 // 步骤1：验证testData属性保留
r($blockTest->printBlock4JsonTest()) && p('view:app') && e('~~');                              // 步骤2：验证app属性被移除
r($blockTest->printBlock4JsonTest()) && p('view:config') && e('~~');                           // 步骤3：验证config属性被移除
r($blockTest->printBlock4JsonTest()) && p('view:lang') && e('~~');                             // 步骤4：验证lang属性被移除
r($blockTest->printBlock4JsonTest()) && p('view:header') && e('~~');                           // 步骤5：验证header属性被移除