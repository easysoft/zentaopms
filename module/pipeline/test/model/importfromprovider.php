#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::importFromProvider();
timeout=0
cid=0

- Jenkins正常导入 >> 大于0
- 流水线名称为空 @0
- 流水线名称重复 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('ops_pipeline')->gen(0);

$providerTable = zenData('ops_provider');
$providerTable->id->range('1-2');
$providerTable->type->range('Jenkins,Gitlab');
$providerTable->name->range('Jenkins测试服务器,GitLab测试服务器');
$providerTable->url->range('http://jenkins.test.com,http://gitlab.test.com');
$providerTable->token->range('YWRtaW46dGVzdHRva2Vu');
$providerTable->deleted->range('0');
$providerTable->gen(2);

zenData('repo')->loadYaml('repo', false, 1)->gen(5);

su('admin');

$pipelineTest = new pipelineModelTest();

$repo          = new stdclass();
$repo->id     = 1;
$repo->spaceID = 1;
$repo->name    = 'TestRepo';
$repo->defaultBranch = 'main';
$repo->connector = '';

$formData           = new stdclass();
$formData->providerID = 1;
$formData->pipeline   = '/job/test-job/';
$formData->name      = 'Jenkins导入测试';
$formData->desc      = '测试导入';

$result = $pipelineTest->importFromProviderTest($repo, $formData);
r($result > 0) && p() && e('1'); // Jenkins正常导入

$formData->name = '';
r($pipelineTest->importFromProviderTest($repo, $formData)) && p() && e('0'); // 名称为空

$formData->name = 'Jenkins导入测试';
r($pipelineTest->importFromProviderTest($repo, $formData)) && p() && e('0'); // 名称重复
