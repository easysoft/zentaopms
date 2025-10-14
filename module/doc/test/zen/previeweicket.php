#!/usr/bin/env php
<?php

/**

title=测试 docZen::previeweicket();
timeout=0
cid=0

- 执行docTest模块的previeweicketTest方法，参数是'setting', array  @1
- 执行docTest模块的previeweicketTest方法，参数是'setting', array  @1
- 执行docTest模块的previeweicketTest方法，参数是'list', array  @1
- 执行docTest模块的previeweicketTest方法，参数是'invalid', array  @0
- 执行docTest模块的previeweicketTest方法，参数是'setting', array  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 准备测试数据
$ticket = zenData('ticket');
$ticket->id->range('1-5');
$ticket->product->range('1-3');
$ticket->title->range('工单标题1,工单标题2,工单标题3,工单标题4,工单标题5');
$ticket->type->range('bug,story,task,support,bug');
$ticket->status->range('wait,doing,done,closed,wait');
$ticket->openedBy->range('admin,user1,user2,admin,user1');
$ticket->openedDate->range('`2024-01-01 00:00:00`,`2024-02-01 00:00:00`,`2024-03-01 00:00:00`,`2024-04-01 00:00:00`,`2024-05-01 00:00:00`');
$ticket->assignedDate->range('`0000-00-00 00:00:00`,`0000-00-00 00:00:00`,`0000-00-00 00:00:00`,`0000-00-00 00:00:00`,`0000-00-00 00:00:00`');
$ticket->realStarted->range('`0000-00-00 00:00:00`,`0000-00-00 00:00:00`,`0000-00-00 00:00:00`,`0000-00-00 00:00:00`,`0000-00-00 00:00:00`');
$ticket->startedDate->range('`0000-00-00 00:00:00`,`0000-00-00 00:00:00`,`0000-00-00 00:00:00`,`0000-00-00 00:00:00`,`0000-00-00 00:00:00`');
$ticket->deadline->range('`0000-00-00`,`0000-00-00`,`0000-00-00`,`0000-00-00`,`0000-00-00`');
$ticket->gen(5);

su('admin');

$docTest = new docTest();

r($docTest->previeweicketTest('setting', array('action' => 'preview', 'product' => 1, 'condition' => 'all'), '')) && p() && e('1');
r($docTest->previeweicketTest('setting', array('action' => 'preview', 'product' => 1, 'condition' => 'customSearch', 'field' => array('status'), 'operator' => array('='), 'value' => array('wait'), 'andor' => array('and')), '')) && p() && e('1');
r($docTest->previeweicketTest('list', array('action' => 'list'), '1,2,3')) && p() && e('1');
r($docTest->previeweicketTest('invalid', array('action' => 'preview'), '')) && p() && e('0');
r($docTest->previeweicketTest('setting', array(), '')) && p() && e('0');