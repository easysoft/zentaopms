#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/compile.unittest.class.php';

zenData('job')->loadYaml('job')->gen(6);
zenData('compile')->gen(6);
zenData('pipeline')->gen(6);
su('admin');

/**

title=测试 compileModel::syncCompile();
timeout=0
cid=1

- 测试步骤1：测试无效参数调用syncCompile方法 >> 期望返回true
- 测试步骤2：测试仅指定repoID调用syncCompile方法 >> 期望返回true
- 测试步骤3：测试仅指定jobID调用syncCompile方法 >> 期望返回true
- 测试步骤4：测试同时指定repoID和jobID调用syncCompile方法 >> 期望返回true
- 测试步骤5：测试不存在的jobID调用syncCompile方法 >> 期望返回true
- 测试步骤6：测试syncCompile方法对job数据的影响 >> 期望执行成功

*/

$compileTest = new compileTest();
r($compileTest->syncCompileTest(0, 0)) && p() && e(true);    // 步骤1：测试无效参数调用
r($compileTest->syncCompileTest(1, 0)) && p() && e(true);    // 步骤2：测试仅指定repoID
r($compileTest->syncCompileTest(0, 1)) && p() && e(true);    // 步骤3：测试仅指定jobID
r($compileTest->syncCompileTest(1, 2)) && p() && e(true);    // 步骤4：测试同时指定两个参数
r($compileTest->syncCompileTest(0, 999)) && p() && e(true);  // 步骤5：测试不存在的jobID
r($compileTest->syncCompileTest(0, 3)) && p() && e(true);    // 步骤6：测试正常job的同步