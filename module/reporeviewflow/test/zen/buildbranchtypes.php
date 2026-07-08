#!/usr/bin/env php
<?php
/**

title=测试 repoZen::buildBranchTypes 方法();
timeout=0
cid=0

- 执行repoTest模块的buildBranchTypesTest方法，参数是1 属性1 @branch_type1
- 执行repoTest模块的buildBranchTypesTest方法，参数是2 属性2 @branch_type2
- 执行repoTest模块的buildBranchTypesTest方法，参数是3 属性3 @branch_type3
- 执行repoTest模块的buildBranchTypesTest方法，参数是4 属性4 @branch_type4
- 执行repoTest模块的buildBranchTypesTest方法  @全部
*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('user')->gen(5);
zenData('repo')->gen(10);
zenData('ops_branch_type')->gen(10);
zenData('ops_review_flow')->gen(10);

su('admin');

$flowTest = new reporeviewflowZenTest();
r($flowTest->buildBranchTypesTest(1)) && p('1') && e('branch_type1');
r($flowTest->buildBranchTypesTest(2)) && p('2') && e('branch_type2');
r($flowTest->buildBranchTypesTest(3)) && p('3') && e('branch_type3');
r($flowTest->buildBranchTypesTest(4)) && p('4') && e('branch_type4');
r($flowTest->buildBranchTypesTest(0)) && p('0') && e('全部');
