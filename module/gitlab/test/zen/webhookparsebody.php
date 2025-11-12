#!/usr/bin/env php
<?php

/**

title=测试 gitlabZen::webhookParseBody();
timeout=0
cid=0

- 测试解析有效的issue类型webhook事件,期望成功解析并返回对象 @1
- 测试解析空的object_kind,期望返回false(类型错误) @type_error_false
- 测试解析不存在的object_kind类型,期望方法未找到 @method_not_found
- 测试解析缺少object_kind字段的body,期望返回false(类型错误) @type_error_false
- 测试解析无效的object_kind(非可调用方法),期望方法未找到 @method_not_found
- 测试验证返回对象包含issue信息,期望labels为空返回null(类型错误) @type_error_null
- 测试验证正确传递gitlabID参数,期望方法能正常处理参数 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

/* 设置 methodName 避免 gitlab 控制器构造函数报错 */
global $app;
$app->setMethodName('test');

$gitlabTest = new gitlabZenTest();

/* 准备测试数据:创建包含有效issue事件的body对象 */
$body1 = new stdclass;
$body1->object_kind = 'issue';
$body1->object_attributes = new stdclass;
$body1->object_attributes->action = 'open';
$body1->object_attributes->title = 'Test Issue';
$body1->object_attributes->description = 'Test description';
$body1->object_attributes->state = 'opened';
$body1->object_attributes->web_url = 'http://gitlab.test/issue/1';
$body1->object_attributes->assignee_id = 1;
$body1->changes = new stdclass;
$body1->labels = array();
$label1 = new stdclass;
$label1->title = 'zentao_bug/123';
$body1->labels[] = $label1;

/* 准备测试数据:空的object_kind */
$body2 = new stdclass;
$body2->object_kind = '';

/* 准备测试数据:不存在的object_kind */
$body3 = new stdclass;
$body3->object_kind = 'nonexistent';

/* 准备测试数据:缺少object_kind字段 */
$body4 = new stdclass;

/* 准备测试数据:无效的object_kind(非可调用方法) */
$body5 = new stdclass;
$body5->object_kind = 'invalid_type';

/* 准备测试数据:issue事件但labels为空 */
$body6 = new stdclass;
$body6->object_kind = 'issue';
$body6->object_attributes = new stdclass;
$body6->object_attributes->action = 'update';
$body6->object_attributes->title = 'Another Issue';
$body6->object_attributes->description = '';
$body6->object_attributes->state = 'opened';
$body6->object_attributes->web_url = 'http://gitlab.test/issue/2';
$body6->changes = new stdclass;
$body6->labels = array();

/* 准备测试数据:issue事件带story标签 */
$body7 = new stdclass;
$body7->object_kind = 'issue';
$body7->object_attributes = new stdclass;
$body7->object_attributes->action = 'open';
$body7->object_attributes->title = 'Story Issue';
$body7->object_attributes->description = 'Story description';
$body7->object_attributes->state = 'opened';
$body7->object_attributes->web_url = 'http://gitlab.test/issue/3';
$body7->object_attributes->assignee_id = 1;
$body7->changes = new stdclass;
$body7->labels = array();
$label7 = new stdclass;
$label7->title = 'zentao_story/456';
$body7->labels[] = $label7;

r($gitlabTest->webhookParseBodyTest($body1, 1) !== false && $gitlabTest->webhookParseBodyTest($body1, 1) !== 'type_error_false' && $gitlabTest->webhookParseBodyTest($body1, 1) !== 'type_error_null' && $gitlabTest->webhookParseBodyTest($body1, 1) !== 'method_not_found') && p() && e('1'); // 测试解析有效的issue类型webhook事件,期望成功解析并返回对象
r($gitlabTest->webhookParseBodyTest($body2, 1)) && p() && e('type_error_false'); // 测试解析空的object_kind,期望返回false(类型错误)
r($gitlabTest->webhookParseBodyTest($body3, 1)) && p() && e('method_not_found'); // 测试解析不存在的object_kind类型,期望方法未找到
r($gitlabTest->webhookParseBodyTest($body4, 1)) && p() && e('type_error_false'); // 测试解析缺少object_kind字段的body,期望返回false(类型错误)
r($gitlabTest->webhookParseBodyTest($body5, 1)) && p() && e('method_not_found'); // 测试解析无效的object_kind(非可调用方法),期望方法未找到
r($gitlabTest->webhookParseBodyTest($body6, 1)) && p() && e('type_error_null'); // 测试验证返回对象包含issue信息,期望labels为空返回null(类型错误)
r($gitlabTest->webhookParseBodyTest($body7, 1) !== false && $gitlabTest->webhookParseBodyTest($body7, 1) !== 'type_error_false' && $gitlabTest->webhookParseBodyTest($body7, 1) !== 'type_error_null' && $gitlabTest->webhookParseBodyTest($body7, 1) !== 'method_not_found') && p() && e('1'); // 测试验证正确传递gitlabID参数,期望方法能正常处理参数