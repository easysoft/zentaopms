#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->createDefaultRegion();
cid=1
pid=1

创建看板10001的默认区域 >> 10001,10001,默认区域
创建看板10002的默认区域 >> 10002,10002,默认区域
创建看板10003的默认区域 >> 10003,10003,默认区域
创建看板10004的默认区域 >> 10004,10004,默认区域
重复创建看板1的默认区域 >> 『区域名称』已经有『默认区域』这条记录了。如果您确定该记录已删除，请到后台-系统-数据-回收站还原。

*/

$kanban1 = new stdclass();
$kanban1->id    = 10001;
$kanban1->space = 10001;

$kanban2 = new stdclass();
$kanban2->id    = 10002;
$kanban2->space = 10002;

$kanban3 = new stdclass();
$kanban3->id    = 10003;
$kanban3->space = 10003;

$kanban4 = new stdclass();
$kanban4->id    = 10004;
$kanban4->space = 10004;

$kanban5 = new stdclass();
$kanban5->id    = 1;
$kanban5->space = 1;

$kanban = new kanbanTest();

r($kanban->createDefaultRegionTest($kanban1)) && p('space,kanban,name') && e('10001,10001,默认区域'); // 创建看板10001的默认区域
r($kanban->createDefaultRegionTest($kanban2)) && p('space,kanban,name') && e('10002,10002,默认区域'); // 创建看板10002的默认区域
r($kanban->createDefaultRegionTest($kanban3)) && p('space,kanban,name') && e('10003,10003,默认区域'); // 创建看板10003的默认区域
r($kanban->createDefaultRegionTest($kanban4)) && p('space,kanban,name') && e('10004,10004,默认区域'); // 创建看板10004的默认区域
r($kanban->createDefaultRegionTest($kanban5)) && p('name:0')            && e('『区域名称』已经有『默认区域』这条记录了。如果您确定该记录已删除，请到后台-系统-数据-回收站还原。'); // 重复创建看板1的默认区域
