#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('user3');

zdTable('kanban')->gen(5);

/**

title=测试 kanbanModel->getByID();
timeout=0
cid=1

- 查看user3用户可见的看板数量 @2
- 查看看板2的名字。属性2 @通用看板2

*/
global $tester;
$tester->loadModel('kanban');

r(count($tester->kanban->getPairs())) && p('')  && e('2');         // 查看user3用户可见的看板数量
r($tester->kanban->getPairs())        && p('2') && e('通用看板2'); // 查看看板2的名字。