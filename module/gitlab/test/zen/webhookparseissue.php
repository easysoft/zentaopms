#!/usr/bin/env php
<?php

/**

title=测试 gitlabZen::webhookParseIssue();
timeout=0
cid=16680

- 测试解析带bug标签的有效issue事件属性objectType @bug
- 测试解析带story标签的有效issue事件属性objectType @story
- 测试解析带task标签的有效issue事件属性objectType @task
- 测试解析空labels的issue事件 @0
- 测试解析无效标签格式的issue事件 @0
- 测试解析完整issue数据含changes属性
 - 属性objectType @bug
 - 属性objectID @999
- 测试解析issue描述markdown转html第issue条的description属性 @<h1>Test Markdown</h1>

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

/* 设置 methodName 避免 gitlab 控制器构造函数报错 */
global $app;
$app->setMethodName('test');

$gitlabTest = new gitlabZenTest();

/* 准备测试数据1:带bug标签的有效issue事件 */
$body1 = new stdclass;
$body1->object_kind = 'issue';
$body1->object_attributes = new stdclass;
$body1->object_attributes->action = 'open';
$body1->object_attributes->title = 'Bug Issue';
$body1->object_attributes->description = 'Test bug description';
$body1->object_attributes->state = 'opened';
$body1->object_attributes->web_url = 'http://gitlab.test/issue/1';
$body1->object_attributes->assignee_id = 1;
$body1->object_attributes->created_at = '2024-01-01 10:00:00';
$body1->object_attributes->updated_at = '2024-01-02 10:00:00';
$body1->object_attributes->due_date = '2024-12-31';
$body1->object_attributes->weight = 3;
$body1->changes = new stdclass;
$body1->changes->state = new stdclass;
$body1->changes->state->previous = 'closed';
$body1->changes->state->current = 'opened';
$body1->labels = array();
$label1 = new stdclass;
$label1->title = 'zentao_bug/123';
$body1->labels[] = $label1;

/* 准备测试数据2:带story标签的有效issue事件 */
$body2 = new stdclass;
$body2->object_kind = 'issue';
$body2->object_attributes = new stdclass;
$body2->object_attributes->action = 'update';
$body2->object_attributes->title = 'Story Issue';
$body2->object_attributes->description = 'Test story description';
$body2->object_attributes->state = 'opened';
$body2->object_attributes->web_url = 'http://gitlab.test/issue/2';
$body2->object_attributes->assignee_id = 2;
$body2->object_attributes->created_at = '2024-01-01 10:00:00';
$body2->object_attributes->updated_at = '2024-01-02 10:00:00';
$body2->object_attributes->weight = 2;
$body2->changes = new stdclass;
$body2->labels = array();
$label2 = new stdclass;
$label2->title = 'zentao_story/456';
$body2->labels[] = $label2;

/* 准备测试数据3:带task标签的有效issue事件 */
$body3 = new stdclass;
$body3->object_kind = 'issue';
$body3->object_attributes = new stdclass;
$body3->object_attributes->action = 'close';
$body3->object_attributes->title = 'Task Issue';
$body3->object_attributes->description = 'Test task description';
$body3->object_attributes->state = 'closed';
$body3->object_attributes->web_url = 'http://gitlab.test/issue/3';
$body3->object_attributes->assignee_id = 3;
$body3->object_attributes->created_at = '2024-01-01 10:00:00';
$body3->object_attributes->updated_at = '2024-01-02 10:00:00';
$body3->object_attributes->due_date = '2024-12-31';
$body3->object_attributes->weight = 1;
$body3->object_attributes->updated_by_id = 5;
$body3->changes = new stdclass;
$body3->labels = array();
$label3 = new stdclass;
$label3->title = 'zentao_task/789';
$body3->labels[] = $label3;

/* 准备测试数据4:空labels的issue事件 */
$body4 = new stdclass;
$body4->object_kind = 'issue';
$body4->object_attributes = new stdclass;
$body4->object_attributes->action = 'open';
$body4->object_attributes->title = 'No Label Issue';
$body4->object_attributes->description = '';
$body4->object_attributes->state = 'opened';
$body4->object_attributes->web_url = 'http://gitlab.test/issue/4';
$body4->changes = new stdclass;
$body4->labels = array();

/* 准备测试数据5:无效标签格式的issue事件 */
$body5 = new stdclass;
$body5->object_kind = 'issue';
$body5->object_attributes = new stdclass;
$body5->object_attributes->action = 'open';
$body5->object_attributes->title = 'Invalid Label Issue';
$body5->object_attributes->description = 'Invalid label format';
$body5->object_attributes->state = 'opened';
$body5->object_attributes->web_url = 'http://gitlab.test/issue/5';
$body5->changes = new stdclass;
$body5->labels = array();
$label5 = new stdclass;
$label5->title = 'invalid_label_format';
$body5->labels[] = $label5;

/* 准备测试数据6:完整issue数据含changes属性 */
$body6 = new stdclass;
$body6->object_kind = 'issue';
$body6->object_attributes = new stdclass;
$body6->object_attributes->action = 'update';
$body6->object_attributes->title = 'Complete Issue';
$body6->object_attributes->description = 'Complete description';
$body6->object_attributes->state = 'opened';
$body6->object_attributes->web_url = 'http://gitlab.test/issue/6';
$body6->object_attributes->assignee_id = 1;
$body6->object_attributes->created_at = '2024-01-01 10:00:00';
$body6->object_attributes->updated_at = '2024-01-02 10:00:00';
$body6->object_attributes->due_date = '2024-12-31';
$body6->object_attributes->weight = 2;
$body6->changes = new stdclass;
$body6->changes->assignee_id = new stdclass;
$body6->changes->assignee_id->previous = 2;
$body6->changes->assignee_id->current = 1;
$body6->labels = array();
$label6 = new stdclass;
$label6->title = 'zentao_bug/999';
$body6->labels[] = $label6;

/* 准备测试数据7:issue描述markdown转html */
$body7 = new stdclass;
$body7->object_kind = 'issue';
$body7->object_attributes = new stdclass;
$body7->object_attributes->action = 'open';
$body7->object_attributes->title = 'Markdown Issue';
$body7->object_attributes->description = '# Test Markdown';
$body7->object_attributes->state = 'opened';
$body7->object_attributes->web_url = 'http://gitlab.test/issue/7';
$body7->object_attributes->assignee_id = 1;
$body7->object_attributes->created_at = '2024-01-01 10:00:00';
$body7->object_attributes->updated_at = '2024-01-02 10:00:00';
$body7->object_attributes->weight = 1;
$body7->changes = new stdclass;
$body7->labels = array();
$label7 = new stdclass;
$label7->title = 'zentao_bug/100';
$body7->labels[] = $label7;

r($gitlabTest->webhookParseIssueTest($body1, 1)) && p('objectType') && e('bug'); // 测试解析带bug标签的有效issue事件
r($gitlabTest->webhookParseIssueTest($body2, 1)) && p('objectType') && e('story'); // 测试解析带story标签的有效issue事件
r($gitlabTest->webhookParseIssueTest($body3, 1)) && p('objectType') && e('task'); // 测试解析带task标签的有效issue事件
r($gitlabTest->webhookParseIssueTest($body4, 1)) && p() && e('0'); // 测试解析空labels的issue事件
r($gitlabTest->webhookParseIssueTest($body5, 1)) && p() && e('0'); // 测试解析无效标签格式的issue事件
r($gitlabTest->webhookParseIssueTest($body6, 1)) && p('objectType,objectID') && e('bug,999'); // 测试解析完整issue数据含changes属性
r($gitlabTest->webhookParseIssueTest($body7, 1)) && p('issue:description') && e('<h1>Test Markdown</h1>'); // 测试解析issue描述markdown转html