#!/usr/bin/env php
<?php

/**

title=测试 gitlabModel::apiCreatePipeline();
timeout=0
cid=0

- 使用有效参数创建流水线（对象参数）@ success
- 使用有效参数创建流水线（字符串参数）@ success
- 使用空的url创建流水线 @ has_error
- 使用空的token创建流水线 @ has_error
- 使用空的projectID创建流水线 @ has_error

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$gitlabTest = new gitlabModelTest();

$url       = 'https://gitlab.example.com';
$token     = 'glpat-test-token';
$projectID = '1';

$paramsObj = new stdclass();
$paramsObj->ref = 'main';
$paramsObj->variables = array(array('key' => 'VAR1', 'value' => 'hello'));

$paramsStr = '{"ref":"main","variables":[{"key":"VAR1","value":"hello"}]}';

r($gitlabTest->apiCreatePipelineTest($url, $token, $projectID, $paramsObj)) && p() && e('success');
r($gitlabTest->apiCreatePipelineTest($url, $token, $projectID, $paramsStr)) && p() && e('success');
r($gitlabTest->apiCreatePipelineTest('', $token, $projectID, $paramsObj)) && p() && e('has_error');
r($gitlabTest->apiCreatePipelineTest($url, '', $projectID, $paramsObj)) && p() && e('has_error');
r($gitlabTest->apiCreatePipelineTest($url, $token, '', $paramsObj)) && p() && e('has_error');
