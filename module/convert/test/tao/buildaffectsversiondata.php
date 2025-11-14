#!/usr/bin/env php
<?php

/**

title=测试 convertTao::buildAffectsVersionData();
timeout=0
cid=15800

- 步骤1：正常情况
 - 属性issue @BUG-001
 - 属性version @v1.0.0
- 步骤2：空值
 - 属性issue @~~
 - 属性version @~~
- 步骤3：只有issue有值
 - 属性issue @FEATURE-123
 - 属性version @~~
- 步骤4：只有version有值
 - 属性issue @~~
 - 属性version @v2.1.0
- 步骤5：包含额外字段
 - 属性issue @TASK-456
 - 属性version @v3.0.0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($convertTest->buildAffectsVersionDataTest(array('issue' => 'BUG-001', 'version' => 'v1.0.0'))) && p('issue,version') && e('BUG-001,v1.0.0'); // 步骤1：正常情况
r($convertTest->buildAffectsVersionDataTest(array('issue' => '', 'version' => ''))) && p('issue,version') && e('~~,~~'); // 步骤2：空值
r($convertTest->buildAffectsVersionDataTest(array('issue' => 'FEATURE-123', 'version' => ''))) && p('issue,version') && e('FEATURE-123,~~'); // 步骤3：只有issue有值
r($convertTest->buildAffectsVersionDataTest(array('issue' => '', 'version' => 'v2.1.0'))) && p('issue,version') && e('~~,v2.1.0'); // 步骤4：只有version有值
r($convertTest->buildAffectsVersionDataTest(array('issue' => 'TASK-456', 'version' => 'v3.0.0', 'extra' => 'ignored'))) && p('issue,version') && e('TASK-456,v3.0.0'); // 步骤5：包含额外字段