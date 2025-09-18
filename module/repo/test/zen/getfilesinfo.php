#!/usr/bin/env php
<?php

/**

title=测试 repoZen::getFilesInfo();
timeout=0
cid=0

- 执行$repoZenTest->getFilesInfoTest($repoZenTest->objectModel->getByID(1), '/test/path', 'master', 'bWFzdGVy', 1) @0
- 执行$repoZenTest->getFilesInfoTest($repoZenTest->objectModel->getByID(2), '/test/path', 'main', 'bWFpbg==', 1) @0
- 执行$repoZenTest->getFilesInfoTest($repoZenTest->objectModel->getByID(3), '/test/path', 'trunk', 'dHJ1bms=', 1) @0
- 执行$repoZenTest->getFilesInfoTest($repoZenTest->objectModel->getByID(1), '', 'master', 'bWFzdGVy', 1) @0
- 执行repoZenTest模块的getFilesInfoTest方法，参数是null, '/test/path', 'master', 'bWFzdGVy', 1  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

$table = zenData('repo');
$table->id->range('1-5');
$table->product->range('1,2,3,1,2');
$table->SCM->range('Git,Gitlab,Subversion,Git,Subversion');
$table->serviceHost->range('0,1,0,0,0');
$table->serviceProject->range('0,1,0,0,0');
$table->name->range('repo1,repo2,repo3,repo4,repo5');
$table->path->range('/test/git,/test/gitlab,/test/svn,/test/git2,/test/svn2');
$table->prefix->range(',,/test,,/test2');
$table->encoding->range('utf-8');
$table->client->range('git,git,svn,git,svn');
$table->account->range('admin');
$table->password->range('123456');
$table->encrypt->range('plain');
$table->acl->range('{"acl":"open"}');
$table->synced->range('1');
$table->commits->range('10,20,30,15,25');
$table->lastSync->range('`2024-01-01 10:00:00`');
$table->desc->range('测试仓库1,测试仓库2,测试仓库3,测试仓库4,测试仓库5');
$table->deleted->range('0');
$table->gen(5);

su('admin');

$repoZenTest = new repoZenTest();

r($repoZenTest->getFilesInfoTest($repoZenTest->objectModel->getByID(1), '/test/path', 'master', 'bWFzdGVy', 1)) && p() && e('0');
r($repoZenTest->getFilesInfoTest($repoZenTest->objectModel->getByID(2), '/test/path', 'main', 'bWFpbg==', 1)) && p() && e('0');
r($repoZenTest->getFilesInfoTest($repoZenTest->objectModel->getByID(3), '/test/path', 'trunk', 'dHJ1bms=', 1)) && p() && e('0');
r($repoZenTest->getFilesInfoTest($repoZenTest->objectModel->getByID(1), '', 'master', 'bWFzdGVy', 1)) && p() && e('0');
r($repoZenTest->getFilesInfoTest(null, '/test/path', 'master', 'bWFzdGVy', 1)) && p() && e('0');