#!/usr/bin/env php
<?php

/**

title=测试 pipelineZen::buildImportForm();
timeout=0
cid=0

- Repo不存在时返回空view @0,0,0,0,0,0,~~
- Repo关联GitLab provider未指定providerID时自动选GitLab @1,1,0,1,2,0,repo-with-gitlab
- Repo关联GitLab provider明确指定Jenkins providerID @1,2,1,0,2,0,~~
- Repo无关联provider自动选系统中的Jenkins provider @2,2,1,0,1,0,~~
- Repo无关联provider指定不存在的providerID @2,999,0,0,1,0,repo-no-provider

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$repoTable = zenData('ops_repo');
$repoTable->id->range('1-2');
$repoTable->name->range('repo-with-gitlab,repo-no-provider');
$repoTable->spaceID->range('0');
$repoTable->gitUID->range('abc123,def456');
$repoTable->defaultBranch->range('main');
$repoTable->providerID->range('1,0');
$repoTable->acl->range('private');
$repoTable->deleted->range('0');
$repoTable->gen(2);

$providerTable = zenData('ops_provider');
$providerTable->id->range('1-2');
$providerTable->type->range('Gitlab,Jenkins');
$providerTable->name->range('GitLab,Jenkins');
$providerTable->url->range('http://gitlab.local,http://jenkins.local');
$providerTable->token->range('YWRtaW46dGVzdHRva2Vu');
$providerTable->deleted->range('0');
$providerTable->gen(2);

$entryTable = zenData('entry');
$entryTable->id->range('1');
$entryTable->code->range('gitfox');
$entryTable->name->range('GitFox');
$entryTable->key->range('test-key');
$entryTable->gen(1);

su('admin');

$pipelineZenTest = new pipelineZenTest();

r($pipelineZenTest->buildImportFormTest(999, 0)) && p('repoID,defaultProviderID,isJenkins,hidePipeline,providersCount,pipelinesCount,defaultName') && e('0,0,0,0,0,0,~~'); // Repo不存在
r($pipelineZenTest->buildImportFormTest(1, 0))   && p('repoID,defaultProviderID,isJenkins,hidePipeline,providersCount,pipelinesCount,defaultName') && e('1,1,0,1,2,0,repo-with-gitlab'); // 自动选GitLab
r($pipelineZenTest->buildImportFormTest(1, 2))   && p('repoID,defaultProviderID,isJenkins,hidePipeline,providersCount,pipelinesCount,defaultName') && e('1,2,1,0,2,0,~~'); // 明确指定Jenkins
r($pipelineZenTest->buildImportFormTest(2, 0))   && p('repoID,defaultProviderID,isJenkins,hidePipeline,providersCount,pipelinesCount,defaultName') && e('2,2,1,0,1,0,~~'); // 自动选Jenkins
r($pipelineZenTest->buildImportFormTest(2, 999)) && p('repoID,defaultProviderID,isJenkins,hidePipeline,providersCount,pipelinesCount,defaultName') && e('2,999,0,0,1,0,repo-no-provider'); // 不存在的provider
