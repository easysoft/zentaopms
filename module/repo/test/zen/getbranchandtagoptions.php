#!/usr/bin/env php
<?php

/**

title=测试 repoZen::getBranchAndTagOptions();
timeout=0
cid=0

- 执行repoZenTest模块的getBranchAndTagOptionsTest方法，参数是$normalScm 第0条的text属性 @分支
- 执行repoZenTest模块的getBranchAndTagOptionsTest方法，参数是$branchOnlyScm 第0条的text属性 @分支
- 执行repoZenTest模块的getBranchAndTagOptionsTest方法，参数是$tagOnlyScm 第0条的text属性 @标签
- 执行repoZenTest模块的getBranchAndTagOptionsTest方法，参数是$emptyScm  @0
- 执行repoZenTest模块的getBranchAndTagOptionsTest方法，参数是null  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen_getbranchandtagoptions.unittest.class.php';

su('admin');

$repoZenTest = new repoZenGetBranchAndTagOptionsTest();

// 创建模拟SCM对象 - 正常情况
$normalScm = new stdClass();
$normalScm->branches = array('master', 'develop', 'feature-branch');
$normalScm->tags = array('v1.0.0', 'v1.1.0', 'v2.0.0');

// 创建模拟SCM对象 - 仅有分支
$branchOnlyScm = new stdClass();
$branchOnlyScm->branches = array('master', 'develop');
$branchOnlyScm->tags = array();

// 创建模拟SCM对象 - 仅有标签
$tagOnlyScm = new stdClass();
$tagOnlyScm->branches = array();
$tagOnlyScm->tags = array('v1.0.0', 'v2.0.0');

// 创建空SCM对象
$emptyScm = new stdClass();
$emptyScm->branches = array();
$emptyScm->tags = array();

r($repoZenTest->getBranchAndTagOptionsTest($normalScm)) && p('0:text') && e('分支');
r($repoZenTest->getBranchAndTagOptionsTest($branchOnlyScm)) && p('0:text') && e('分支');
r($repoZenTest->getBranchAndTagOptionsTest($tagOnlyScm)) && p('0:text') && e('标签');
r($repoZenTest->getBranchAndTagOptionsTest($emptyScm)) && p() && e('0');
r($repoZenTest->getBranchAndTagOptionsTest(null)) && p() && e('0');