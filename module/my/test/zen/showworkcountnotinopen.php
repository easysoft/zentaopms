#!/usr/bin/env php
<?php

/**

title=测试 myZen::showWorkCountNotInOpen();
timeout=0
cid=1

- 测试是否是ipd版本属性isIPD @1
- 测试获取bug数量属性bug @0
- 测试获取task数量属性task @0
- 测试获取ticket数量属性ticket @0
- 测试获取feedback数量属性feedback @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('task')->gen(5);
zenData('bug')->gen(5);
zenData('user')->gen(2);
zenData('feedback')->gen(10);

$ticket = zenData('ticket');
$ticket->assignedDate->range('[2023-01-01 00:00:00, 2023-12-31 23:59:59]');
$ticket->realStarted->range('[2023-01-01 00:00:00, 2023-12-31 23:59:59]');
$ticket->startedDate->range('[2023-01-01 00:00:00, 2023-12-31 23:59:59]');
$ticket->openedDate->range('[2023-01-01 00:00:00, 2023-12-31 23:59:59]');
$ticket->activatedDate->range('[2023-01-01 00:00:00, 2023-12-31 23:59:59]');
$ticket->closedDate->range('[2023-01-01 00:00:00, 2023-12-31 23:59:59]');
$ticket->finishedDate->range('[2023-01-01 00:00:00, 2023-12-31 23:59:59]');
$ticket->resolvedDate->range('[2023-01-01 00:00:00, 2023-12-31 23:59:59]');
$ticket->editedDate->range('[2023-01-01 00:00:00, 2023-12-31 23:59:59]');
$ticket->deadline->range('[2023-12-01 00:00:00, 2023-12-31 23:59:59]');
$ticket->gen(2);
su('admin');

global $config, $tester;
$config->edition = 'ipd';
$config->vision  = 'rnd';
$tester->app->rawModule  = 'my';
$tester->app->rawMethod  = 'work';
$tester->app->tab        = 'my';
$tester->app->moduleName = 'my';
$tester->app->methodName = 'work';

$myTest = new myZenTest();
$count  = array('task' => 0,  'bug' => 0, 'ticket' => 0, 'feedback' => 0);

r($myTest->showWorkCountNotInOpenTest($count)) && p('isIPD')    && e('1'); // 测试是否是ipd版本
r($myTest->showWorkCountNotInOpenTest($count)) && p('bug')      && e('0'); // 测试获取bug数量
r($myTest->showWorkCountNotInOpenTest($count)) && p('task')     && e('0'); // 测试获取task数量
r($myTest->showWorkCountNotInOpenTest($count)) && p('ticket')   && e('0'); // 测试获取ticket数量
r($myTest->showWorkCountNotInOpenTest($count)) && p('feedback') && e('0'); // 测试获取feedback数量
