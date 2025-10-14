#!/usr/bin/env php
<?php

/**

title=- 步骤4：确认消息生成 @提醒：有Bug转为了任务
timeout=0
cid=201

- 执行bugTest模块的responseAfterBatchEditTest方法，参数是array  @success
- 执行bugTest模块的responseAfterBatchEditTest方法，参数是array  @success
- 执行bugTest模块的responseAfterBatchEditTest方法，参数是array  @自定义保存成功消息
- 执行bugTest模块的responseAfterBatchEditTest方法，参数是array  @提醒：有Bug转为了任务 #201，请确认是否需要查看？
- 执行bugTest模块的responseAfterBatchEditTest方法，参数是array  @边界测试消息

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

su('admin');

$bugTest = new bugTest();

r($bugTest->responseAfterBatchEditTest(array(101 => 'task101'), '', 'result')) && p() && e('success');
r($bugTest->responseAfterBatchEditTest(array(), '', 'result')) && p() && e('success');  
r($bugTest->responseAfterBatchEditTest(array(), '自定义保存成功消息', 'message')) && p() && e('自定义保存成功消息');
r($bugTest->responseAfterBatchEditTest(array(201 => 'task201'), '', 'confirm')) && p() && e('提醒：有Bug转为了任务 #201，请确认是否需要查看？');
r($bugTest->responseAfterBatchEditTest(array(), '边界测试消息', 'message')) && p() && e('边界测试消息');