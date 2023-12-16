#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanbanregion')->gen(1);

/**

title=测试 kanbanModel->createDefaultRegion();
timeout=0
cid=1

- 创建看板10001的默认区域
 - 属性space @10001
 - 属性kanban @10001
 - 属性name @默认区域
- 创建看板10002的默认区域
 - 属性space @10002
 - 属性kanban @10002
 - 属性name @默认区域
- 创建看板10003的默认区域
 - 属性space @10003
 - 属性kanban @10003
 - 属性name @默认区域
- 创建看板10004的默认区域
 - 属性space @10004
 - 属性kanban @10004
 - 属性name @默认区域
- 重复创建看板1的默认区域第name条的0属性 @『区域名称』已经有『默认区域』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。

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
r($kanban->createDefaultRegionTest($kanban5)) && p('name:0')            && e('『区域名称』已经有『默认区域』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。'); // 重复创建看板1的默认区域