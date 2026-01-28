#!/usr/bin/env php
<?php

/**

title=测试 convertTao::buildFixVersionData();
timeout=0
cid=15813

- 执行convertTest模块的buildFixVersionDataTest方法，参数是array 属性issue @TASK-123
- 执行convertTest模块的buildFixVersionDataTest方法，参数是array 属性version @v2
- 执行convertTest模块的buildFixVersionDataTest方法，参数是array 属性issue @~~
- 执行convertTest模块的buildFixVersionDataTest方法，参数是array 属性version @v1.0-beta
- 执行convertTest模块的buildFixVersionDataTest方法，参数是array 属性issue @ISSUE-789

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTaoTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($convertTest->buildFixVersionDataTest(array('issue' => 'TASK-123', 'version' => '1.0.0'))) && p('issue') && e('TASK-123');
r($convertTest->buildFixVersionDataTest(array('issue' => '1', 'version' => 'v2'))) && p('version') && e('v2');
r($convertTest->buildFixVersionDataTest(array('issue' => '', 'version' => ''))) && p('issue') && e('~~');
r($convertTest->buildFixVersionDataTest(array('issue' => 'BUG-456&test', 'version' => 'v1.0-beta'))) && p('version') && e('v1.0-beta');
r($convertTest->buildFixVersionDataTest(array('issue' => 'ISSUE-789', 'version' => '2.1.0'))) && p('issue') && e('ISSUE-789');