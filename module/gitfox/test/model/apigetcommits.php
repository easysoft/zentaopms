#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apigetcommits();
timeout=0
cid=0

- 步骤 1：真实代码库的提交总数为 1 @1
- 步骤 2：真实代码库的分页页码为 1 @1
- 步骤 3：真实代码库的分页条数为 20 @20
- 步骤 4：真实代码库的初始提交标题为 initial commit @initial commit
- 步骤 5：不存在的代码库返回 false @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
include dirname(__FILE__, 2) . '/lib/repodata.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();
$repoData   = new gitfoxRepoData();
$repo       = $repoData->createRepo();
$repoID     = (int)$repo->id;
$params     = array('page' => 1, 'pageSize' => 20, 'ref' => $repo->defaultBranch);

r($gitfoxTest->apiGetCommitsTotalTest($repoID, $params)) && p() && e('1');     // 步骤 1：真实代码库的提交总数为 1
r($gitfoxTest->apiGetCommitsTest($repoID, $params)) && p('pager:page') && e('1'); // 步骤 2：真实代码库的分页页码为 1
r($gitfoxTest->apiGetCommitsTest($repoID, $params)) && p('pager:pageSize') && e('20'); // 步骤 3：真实代码库的分页条数为 20
r($gitfoxTest->apiGetCommitsFirstTitleTest($repoID, $params)) && p() && e('initial commit'); // 步骤 4：真实代码库的初始提交标题为 initial commit
r($gitfoxTest->apiGetCommitsTest(999999, $params)) && p() && e('0');           // 步骤 5：不存在的代码库返回 false

$repoData->cleanup();
