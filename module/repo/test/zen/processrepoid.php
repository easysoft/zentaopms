#!/usr/bin/env php
<?php

/**

title=测试 repoZen::processRepoID();
timeout=0
cid=0

- 执行repoTest模块的processRepoIDTest方法，参数是1, 1, array  @1
- 执行repoTest模块的processRepoIDTest方法，参数是0, 1, array  @1
- 执行repoTest模块的processRepoIDTest方法，参数是2, 1, array  @2
- 执行repoTest模块的processRepoIDTest方法，参数是3, 2, array  @3
- 执行repoTest模块的processRepoIDTest方法，参数是999, 1, array  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

$table = zenData('repo');
$table->id->range('1-10');
$table->name->range('test_repo{1-10}');
$table->SCM->range('Git{5},Gitlab{3},Subversion{2}');
$table->product->range('1,2,3');
$table->projects->range('1,2,3');
$table->deleted->range('0');
$table->gen(10);

$table = zenData('project');
$table->id->range('1-5');
$table->name->range('project{1-5}');
$table->type->range('project');
$table->deleted->range('0');
$table->gen(5);

su('admin');

$repoTest = new repoZenTest();

r($repoTest->processRepoIDTest(1, 1, array('Git'))) && p() && e('1');
r($repoTest->processRepoIDTest(0, 1, array('Git'))) && p() && e('1');
r($repoTest->processRepoIDTest(2, 1, array('Git', 'Gitlab'))) && p() && e('2');
r($repoTest->processRepoIDTest(3, 2, array('Git', 'Gitlab'))) && p() && e('3');
r($repoTest->processRepoIDTest(999, 1, array('Git'))) && p() && e('1');