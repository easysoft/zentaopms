#!/usr/bin/env php
<?php

/**

title=测试 repoModel::getLatestCommit();
timeout=0
cid=18067

- 执行repo模块的getLatestCommitTest方法，参数是1
 - 属性id @1
 - 属性revision @c808480afe22d3a55d94e91c59a8f3170212ade0
- 执行repo模块的getLatestCommitTest方法，参数是3
 - 属性id @2
 - 属性commit @2
- 执行repo模块的getLatestCommitTest方法，参数是2  @0
- 执行repo模块的getLatestCommitTest方法，参数是4
 - 属性id @6
 - 属性revision @3
- 执行repo模块的getLatestCommitTestWithoutCount方法，参数是1
 - 属性id @1
 - 属性revision @c808480afe22d3a55d94e91c59a8f3170212ade0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';

zenData('repo')->loadYaml('repo')->gen(4);
zenData('repohistory')->loadYaml('repohistory')->gen(6);

$table = zenData('repobranch');
$table->revision->range('1-3');
$table->branch->range('master,develop,feature');
$table->gen(3);

su('admin');

$repo = new repoTest();

r($repo->getLatestCommitTest(1)) && p('id,revision') && e('1,c808480afe22d3a55d94e91c59a8f3170212ade0');
r($repo->getLatestCommitTest(3)) && p('id,commit') && e('2,2');
r($repo->getLatestCommitTest(2)) && p() && e('0');
r($repo->getLatestCommitTest(4)) && p('id,revision') && e('6,3');
r($repo->getLatestCommitTestWithoutCount(1)) && p('id,revision') && e('1,c808480afe22d3a55d94e91c59a8f3170212ade0');