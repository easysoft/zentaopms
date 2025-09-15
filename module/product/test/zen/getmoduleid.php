#!/usr/bin/env php
<?php

/**

title=测试 productZen::getModuleId();
timeout=0
cid=0

- 步骤1：browseType为bymodule时直接返回param @123
- 步骤2：product tab下有storyModule cookie @789
- 步骤3：project tab下有storyModuleParam cookie @999
- 步骤4：bysearch类型忽略cookie @0
- 步骤5：bybranch类型忽略cookie @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$productTest = new productTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($productTest->getModuleIdTest(123, 'bymodule')) && p() && e('123'); // 步骤1：browseType为bymodule时直接返回param
r($productTest->getModuleIdTest(456, 'unclosed', 'product', '789', '')) && p() && e('789'); // 步骤2：product tab下有storyModule cookie
r($productTest->getModuleIdTest(456, 'unclosed', 'project', '', '999')) && p() && e('999'); // 步骤3：project tab下有storyModuleParam cookie
r($productTest->getModuleIdTest(456, 'bysearch', 'product', '789', '')) && p() && e('0'); // 步骤4：bysearch类型忽略cookie
r($productTest->getModuleIdTest(456, 'bybranch', 'product', '789', '')) && p() && e('0'); // 步骤5：bybranch类型忽略cookie