#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanbanregion')->gen(0);
zdTable('kanbancolumn')->gen(0);
zdTable('kanbanlane')->gen(0);

/**

title=测试 kanbanModel->createRDRegion();
timeout=0
cid=1

- 测试创建执行10001的执行看板区域
 - 属性name @默认区域
 - 属性kanban @100001
- 测试创建执行10002的执行看板区域
 - 属性name @默认区域
 - 属性kanban @100002
- 测试创建执行10003的执行看板区域
 - 属性name @默认区域
 - 属性kanban @100003
- 测试创建执行10004的执行看板区域
 - 属性name @默认区域
 - 属性kanban @100004
- 测试创建执行10005的执行看板区域
 - 属性name @默认区域
 - 属性kanban @100005

*/

$execution1 = new stdclass();
$execution1->id = '100001';

$execution2 = new stdclass();
$execution2->id = '100002';

$execution3 = new stdclass();
$execution3->id = '100003';

$execution4 = new stdclass();
$execution4->id = '100004';

$execution5 = new stdclass();
$execution5->id = '100005';

$kanban = new kanbanTest();

r($kanban->createRDRegionTest($execution1)) && p('name,kanban') && e('默认区域,100001'); // 测试创建执行10001的执行看板区域
r($kanban->createRDRegionTest($execution2)) && p('name,kanban') && e('默认区域,100002'); // 测试创建执行10002的执行看板区域
r($kanban->createRDRegionTest($execution3)) && p('name,kanban') && e('默认区域,100003'); // 测试创建执行10003的执行看板区域
r($kanban->createRDRegionTest($execution4)) && p('name,kanban') && e('默认区域,100004'); // 测试创建执行10004的执行看板区域
r($kanban->createRDRegionTest($execution5)) && p('name,kanban') && e('默认区域,100005'); // 测试创建执行10005的执行看板区域