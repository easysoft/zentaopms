#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::getBrowseBranch();
timeout=0
cid=0

- 步骤1:传入正常分支名称 @branch1
- 步骤2:传入空字符串且preBranch为branch2 @branch2
- 步骤3:传入空字符串且preBranch为空 @0
- 步骤4:传入有效分支名称忽略preBranch @main
- 步骤5:传入分支名称为0 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

su('admin');

$testcaseZenTest = new testcaseZenTest();

r($testcaseZenTest->getBrowseBranchTest('branch1', 'default')) && p() && e('branch1'); // 步骤1:传入正常分支名称
r($testcaseZenTest->getBrowseBranchTest('', 'branch2')) && p() && e('branch2'); // 步骤2:传入空字符串且preBranch为branch2
r($testcaseZenTest->getBrowseBranchTest('', '')) && p() && e('0'); // 步骤3:传入空字符串且preBranch为空
r($testcaseZenTest->getBrowseBranchTest('main', 'ignored')) && p() && e('main'); // 步骤4:传入有效分支名称忽略preBranch
r($testcaseZenTest->getBrowseBranchTest('0', 'default')) && p() && e('0'); // 步骤5:传入分支名称为0