#!/usr/bin/env php
<?php

/**

title=测试 webhookModel::getViewLink();
timeout=0
cid=19701

- 执行webhookTest模块的getViewLinkTest方法，参数是'product', 1  @product-view-1.html
- 执行webhookTest模块的getViewLinkTest方法，参数是'case', 1  @testcase-view-1.html
- 执行webhookTest模块的getViewLinkTest方法，参数是'kanbancard', 1  @kanban-view-1.html
- 执行webhookTest模块的getViewLinkTest方法，参数是'meeting', 1  @meeting-view-1.html#app=project
- 执行webhookTest模块的getViewLinkTest方法，参数是'', 0  @-view-0.html

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/webhook.unittest.class.php';

// 准备测试数据
$kanbanTable = zenData('kanbancard');
$kanbanTable->id->range('1-10');
$kanbanTable->kanban->range('1-5');
$kanbanTable->gen(5);

$meetingTable = zenData('meeting');
$meetingTable->id->range('1-10');
$meetingTable->project->range('1-3,0{3}');
$meetingTable->gen(5);

su('admin');

$webhookTest = new webhookTest();

r($webhookTest->getViewLinkTest('product', 1)) && p() && e('product-view-1.html');
r($webhookTest->getViewLinkTest('case', 1)) && p() && e('testcase-view-1.html');
r($webhookTest->getViewLinkTest('kanbancard', 1)) && p() && e('kanban-view-1.html');
r($webhookTest->getViewLinkTest('meeting', 1)) && p() && e('meeting-view-1.html#app=project');
r($webhookTest->getViewLinkTest('', 0)) && p() && e('-view-0.html');