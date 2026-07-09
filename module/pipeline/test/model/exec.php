#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::exec();
timeout=0
cid=0

- 测试不存在的流水线ID @not_found
- 测试gitlab引擎执行成功 @success
- 测试gitlab引擎API返回错误 @api_error
- 测试gitlab引擎API返回空响应 @empty_response
- 测试边界值ID为0 @not_found

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$pipelineTest = new pipelineModelTest();

$variables = new stdclass();
$variables->gitRef = 'main';

r($pipelineTest->execTest(999, $variables)) && p() && e('not_found');
r($pipelineTest->execTest(1, $variables)) && p() && e('success');
r($pipelineTest->execTest(3, $variables)) && p() && e('api_error');
r($pipelineTest->execTest(4, $variables)) && p() && e('empty_response');
r($pipelineTest->execTest(0, $variables)) && p() && e('not_found');
