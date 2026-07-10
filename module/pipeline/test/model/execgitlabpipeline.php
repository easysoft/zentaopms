#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::execGitlabPipeline();
timeout=0
cid=0

- 测试缺少providerID的流水线 @api_error
- 测试正常执行gitlab流水线成功 @success
- 测试API返回错误信息 @api_error
- 测试API返回空响应 @api_error
- 测试无自定义参数执行成功 @success

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$pipelineTest = new pipelineModelTest();

r($pipelineTest->execGitlabPipelineTest(1)) && p() && e('api_error');
r($pipelineTest->execGitlabPipelineTest(2)) && p() && e('success');
r($pipelineTest->execGitlabPipelineTest(3)) && p() && e('api_error');
r($pipelineTest->execGitlabPipelineTest(4)) && p() && e('api_error');
r($pipelineTest->execGitlabPipelineTest(5)) && p() && e('success');
