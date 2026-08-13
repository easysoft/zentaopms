#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1);
su('admin');

/**

title=测试 codescanZen->processExecBranch();
timeout=0
cid=0

- 执行test模块的processExecBranchTest方法，参数是0, 0  @1
- 执行test模块的processExecBranchTest方法，参数是1, 0  @1
- 执行test模块的processExecBranchTest方法，参数是0, 1  @1
- 执行test模块的processExecBranchTest方法，参数是2, 0  @1
- 执行test模块的processExecBranchTest方法，参数是1, 1  @1

*/

$test = new codescanZenTest();

r(is_array($test->processExecBranchTest(0, 0))) && p() && e('1');
r(is_array($test->processExecBranchTest(1, 0))) && p() && e('1');
r(is_array($test->processExecBranchTest(0, 1))) && p() && e('1');
r(is_array($test->processExecBranchTest(2, 0))) && p() && e('1');
r(is_array($test->processExecBranchTest(1, 1))) && p() && e('1');