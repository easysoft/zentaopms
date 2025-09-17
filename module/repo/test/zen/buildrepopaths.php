#!/usr/bin/env php
<?php

/**

title=测试 repoZen::buildRepoPaths();
timeout=0
cid=0

- 执行repoZenTest模块的buildRepoPathsTest方法，参数是array 第0条的text属性 @other
- 执行repoZenTest模块的buildRepoPathsTest方法，参数是array 第1条的text属性 @project
- 执行repoZenTest模块的buildRepoPathsTest方法，参数是array 第0条的text属性 @single
- 执行repoZenTest模块的buildRepoPathsTest方法，参数是array  @0
- 执行repoZenTest模块的buildRepoPathsTest方法，参数是array 第0条的text属性 @test-repo

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

su('admin');

$repoZenTest = new repoZenTest();

r($repoZenTest->buildRepoPathsTest(array(1 => 'project/module1', 2 => 'project/module2', 3 => 'other/test'))) && p('0:text') && e('other');
r($repoZenTest->buildRepoPathsTest(array(1 => 'project/module1', 2 => 'project/module2', 3 => 'other/test'))) && p('1:text') && e('project');
r($repoZenTest->buildRepoPathsTest(array(1 => 'single/path'))) && p('0:text') && e('single');
r($repoZenTest->buildRepoPathsTest(array())) && p() && e('0');
r($repoZenTest->buildRepoPathsTest(array(1 => 'test-repo/sub_module'))) && p('0:text') && e('test-repo');