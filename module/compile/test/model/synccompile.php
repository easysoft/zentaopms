#!/usr/bin/env php
<?php

/**

title=测试 compileModel::syncCompile();
timeout=0
cid=15754

- 测试步骤1：无参数调用 @1
- 测试步骤2：仅指定repoID @1
- 测试步骤3：仅指定jobID @1
- 测试步骤4：同时指定两个参数 @1
- 测试步骤5：不存在的jobID @1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/compile.unittest.class.php';

// 2. 简化数据准备
zenData('job')->gen(5);
zenData('pipeline')->gen(3);
zenData('repo')->gen(3);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$compileTest = new compileTest();

// 5. 执行测试步骤（至少5个）
r($compileTest->syncCompileTest(0, 0)) && p() && e('1');      // 测试步骤1：无参数调用
r($compileTest->syncCompileTest(1, 0)) && p() && e('1');      // 测试步骤2：仅指定repoID
r($compileTest->syncCompileTest(0, 1)) && p() && e('1');      // 测试步骤3：仅指定jobID
r($compileTest->syncCompileTest(1, 2)) && p() && e('1');      // 测试步骤4：同时指定两个参数
r($compileTest->syncCompileTest(0, 999)) && p() && e('1');    // 测试步骤5：不存在的jobID