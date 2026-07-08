#!/usr/bin/env php
<?php

/**

title=测试 pipelineZen::getJenkinsPipelineList();
timeout=0
cid=0

- Jenkins类型provider返回job列表 @job_sample_1
- 非Jenkins类型provider(Gitlab)返回空数组 @0
- 不存在的providerID返回空数组 @0
- providerID为0返回空数组 @0
- Jenkins类型provider带repoID参数返回job列表 @job_sample_2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$providerTable = zenData('ops_provider');
$providerTable->id->range('1-2');
$providerTable->type->range('Jenkins,Gitlab');
$providerTable->name->range('Jenkins测试服务器,GitLab测试服务器');
$providerTable->url->range('http://jenkins.test.com,http://gitlab.test.com');
$providerTable->token->range('YWRtaW46dGVzdHRva2Vu');
$providerTable->deleted->range('0');
$providerTable->gen(2);

su('admin');

$pipelineZenTest = new pipelineZenTest();

r($pipelineZenTest->getJenkinsPipelineListTest(1)) && p('job_sample_1') && e('job_sample_1'); // Jenkins正常返回job列表
r($pipelineZenTest->getJenkinsPipelineListTest(2)) && p() && e('0'); // Gitlab类型返回空数组
r($pipelineZenTest->getJenkinsPipelineListTest(999)) && p() && e('0'); // 不存在的provider返回空数组
r($pipelineZenTest->getJenkinsPipelineListTest(0)) && p() && e('0'); // providerID为0返回空数组
r($pipelineZenTest->getJenkinsPipelineListTest(1, 1)) && p('job_sample_2') && e('job_sample_2'); // 带repoID参数返回job列表
