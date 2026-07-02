#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::getCommits();
timeout=0
cid=0

- 步骤 1：HTTP 返回空 commits 时返回空数组 @0
- 步骤 2：HTTP 返回一条 commit 时回填 revision 字段 @sha-a
- 步骤 3：HTTP 返回 commit 时使用 title 生成 comment 字段 @fix bug
- 步骤 4：query 携带 committer 时被测方法仍能正常返回 commit @sha-b
- 步骤 5：HTTP 返回非数组数据时被测方法返回空数组 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/* ops_repohistory 表结构由 test/data/ops_repohistory.sql 建，数据由 zenData + loadYaml 灌入。 */
global $tester, $app;
$schemaFile = $app->getAppRoot() . 'test' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'ops_repohistory.sql';
if(file_exists($schemaFile)) $tester->dbh->exec(file_get_contents($schemaFile));

zenData('ops_repohistory')->loadYaml('ops_repohistory')->gen(3);

su('admin');

$gitfoxTest = new gitfoxModelTest();

$repo = new stdclass();
$repo->id       = 1;
$repo->scmType  = 'git';
$repo->client   = 'http://gitfox.test';
$repo->apiPath  = 'http://gitfox.test/api/v1/';
$repo->password = 'token';

$emptyBody   = json_encode(array('data' => array('commits' => array())));
$oneBody     = json_encode(array('data' => array('commits' => array(array('sha' => 'sha-a', 'title' => 'fix bug', 'committer_name' => 'admin', 'committed_date' => '2024-01-01T00:00:00Z')))));
$anotherBody = json_encode(array('data' => array('commits' => array(array('sha' => 'sha-b', 'title' => 'feat: init', 'committer_name' => 'admin', 'committed_date' => '2024-01-02T00:00:00Z')))));
$badBody     = json_encode(array('data' => 'not-array'));

$query = new stdclass();
$query->commit    = '';
$query->committer = 'admin';
$query->begin     = '';
$query->end       = '';

r(count((array)$gitfoxTest->getCommitsTest($repo, '', null, '', '', null, $emptyBody))) && p() && e('0'); // 步骤 1
r($gitfoxTest->getCommitsTest($repo, '', null, '', '', null, $oneBody)) && p('0:revision') && e('sha-a'); // 步骤 2
r($gitfoxTest->getCommitsTest($repo, '', null, '', '', null, $oneBody)) && p('0:comment') && e('fix bug'); // 步骤 3
r($gitfoxTest->getCommitsTest($repo, '', null, '', '', $query, $anotherBody)) && p('0:revision') && e('sha-b'); // 步骤 4
r(count((array)$gitfoxTest->getCommitsTest($repo, '', null, '', '', null, $badBody))) && p() && e('0'); // 步骤 5
