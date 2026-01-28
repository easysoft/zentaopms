#!/usr/bin/env php
<?php

/**

title=测试 webhookModel::getViewLink();
timeout=0
cid=19701

- 执行webhookTest模块的getViewLinkTest方法，参数是'product', 1  @/getviewlink.php?m=product&f=view&id=1
- 执行webhookTest模块的getViewLinkTest方法，参数是'case', 1  @/getviewlink.php?m=testcase&f=view&id=1
- 执行webhookTest模块的getViewLinkTest方法，参数是'kanbancard', 1  @/getviewlink.php?m=kanban&f=view&id=1
- 执行webhookTest模块的getViewLinkTest方法，参数是'meeting', 1  @/getviewlink.php?m=meeting&f=view&id=1#app=project
- 执行webhookTest模块的getViewLinkTest方法，参数是'', 0  @/getviewlink.php?m=&f=view&id=0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

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

$webhookTest = new webhookModelTest();

r($webhookTest->getViewLinkTest('product', 1)) && p() && e('/getviewlink.php?m=product&f=view&id=1');
r($webhookTest->getViewLinkTest('case', 1)) && p() && e('/getviewlink.php?m=testcase&f=view&id=1');
r($webhookTest->getViewLinkTest('kanbancard', 1)) && p() && e('/getviewlink.php?m=kanban&f=view&id=1');
r($webhookTest->getViewLinkTest('meeting', 1)) && p() && e('/getviewlink.php?m=meeting&f=view&id=1#app=project');
r($webhookTest->getViewLinkTest('', 0)) && p() && e('/getviewlink.php?m=&f=view&id=0');