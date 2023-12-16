#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanbangroup')->gen(5);
zdTable('kanbanregion')->gen(5);

/**

title=测试 kanbanModel->createGroup();
timeout=0
cid=1

- 测试创建看板1 区域1的看板组
 - 属性kanban @1
 - 属性region @1
 - 属性order @6
- 测试创建看板2 区域2的看板组
 - 属性kanban @2
 - 属性region @2
 - 属性order @11
- 测试创建看板3 区域3的看板组
 - 属性kanban @3
 - 属性region @3
 - 属性order @16
- 测试创建看板4 区域4的看板组
 - 属性kanban @4
 - 属性region @4
 - 属性order @21
- 测试创建看板5 区域5的看板组
 - 属性kanban @5
 - 属性region @5
 - 属性order @26

*/

$kanbanIDList = array('1', '2', '3', '4', '5');
$regionIDList = array('1', '2', '3', '4', '5');

$kanban = new kanbanTest();

r($kanban->createGroupTest($kanbanIDList[0], $regionIDList[0])) && p('kanban,region,order') && e('1,1,6');  // 测试创建看板1 区域1的看板组
r($kanban->createGroupTest($kanbanIDList[1], $regionIDList[1])) && p('kanban,region,order') && e('2,2,11'); // 测试创建看板2 区域2的看板组
r($kanban->createGroupTest($kanbanIDList[2], $regionIDList[2])) && p('kanban,region,order') && e('3,3,16'); // 测试创建看板3 区域3的看板组
r($kanban->createGroupTest($kanbanIDList[3], $regionIDList[3])) && p('kanban,region,order') && e('4,4,21'); // 测试创建看板4 区域4的看板组
r($kanban->createGroupTest($kanbanIDList[4], $regionIDList[4])) && p('kanban,region,order') && e('5,5,26'); // 测试创建看板5 区域5的看板组