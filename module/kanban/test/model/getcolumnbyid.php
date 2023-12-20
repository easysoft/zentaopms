#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanbancolumn')->gen(5);

/**

title=测试 kanbanModel->getColumnByID();
timeout=0
cid=1

- 测试查询看板列1的信息
 - 属性type @column1
 - 属性region @1
 - 属性name @未开始
 - 属性limit @100
- 测试查询看板列2的信息
 - 属性type @column2
 - 属性region @1
 - 属性name @进行中
 - 属性limit @100
- 测试查询看板列3的信息
 - 属性type @column3
 - 属性region @1
 - 属性name @已完成
 - 属性limit @100
- 测试查询看板列4的信息
 - 属性type @column4
 - 属性region @1
 - 属性name @已关闭
 - 属性limit @100
- 测试查询看板列5的信息
 - 属性type @column5
 - 属性region @2
 - 属性name @未开始
 - 属性limit @100
- 测试查询不存在看板列的信息 @0

*/

$columnIDList = array('1', '2', '3', '4', '5', '1000001');

$kanban = new kanbanTest();

r($kanban->getColumnByIDTest($columnIDList[0])) && p('type,region,name,limit') && e('column1,1,未开始,100'); // 测试查询看板列1的信息
r($kanban->getColumnByIDTest($columnIDList[1])) && p('type,region,name,limit') && e('column2,1,进行中,100'); // 测试查询看板列2的信息
r($kanban->getColumnByIDTest($columnIDList[2])) && p('type,region,name,limit') && e('column3,1,已完成,100'); // 测试查询看板列3的信息
r($kanban->getColumnByIDTest($columnIDList[3])) && p('type,region,name,limit') && e('column4,1,已关闭,100'); // 测试查询看板列4的信息
r($kanban->getColumnByIDTest($columnIDList[4])) && p('type,region,name,limit') && e('column5,2,未开始,100'); // 测试查询看板列5的信息
r($kanban->getColumnByIDTest($columnIDList[5])) && p('')                       && e('0');                    // 测试查询不存在看板列的信息