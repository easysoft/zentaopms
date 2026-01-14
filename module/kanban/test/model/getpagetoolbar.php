#!/usr/bin/env php
<?php

/**

title=测试 kanbanModel::getPageToolBar();
timeout=0
cid=16934

- 执行$userToolbar @161
- 执行$adminToolbar) > strlen($userToolbar @1
- 执行$closedKanbanToolbar, '激活看板') !== false @1
- 执行$adminToolbar, 'icon-fullscreen') !== false @1
- 执行$adminToolbar, '设置') !== false @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('kanban')->gen(3);
zenData('user')->gen(5);

su('user1');

$kanbanTest = new kanbanModelTest();

// 创建测试用的看板对象
$activeKanban = new stdclass();
$activeKanban->id = 1;
$activeKanban->status = 'active';

$closedKanban = new stdclass();
$closedKanban->id = 2;
$closedKanban->status = 'closed';

$userToolbar = $kanbanTest->getPageToolBarTest($activeKanban);

su('admin');
$adminToolbar = $kanbanTest->getPageToolBarTest($activeKanban);
$closedKanbanToolbar = $kanbanTest->getPageToolBarTest($closedKanban);

r(strlen($userToolbar)) && p() && e('161');
r(strlen($adminToolbar) > strlen($userToolbar)) && p() && e('1');
r(strpos($closedKanbanToolbar, '激活看板') !== false) && p() && e('1');
r(strpos($adminToolbar, 'icon-fullscreen') !== false) && p() && e('1');
r(strpos($adminToolbar, '设置') !== false) && p() && e('1');