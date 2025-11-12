#!/usr/bin/env php
<?php

/**

title=测试 bugZen::assignKanbanVars();
timeout=0
cid=0

- 测试看板类型执行无output参数情况属性executionType @kanban
- 测试sprint类型执行无output参数情况属性executionType @sprint
- 测试stage类型执行有regionID参数情况
 - 属性executionType @stage
 - 属性regionID @5
- 测试有groupID参数情况属性executionType @kanban
- 测试有regionID和laneID参数情况
 - 属性executionType @sprint
 - 属性regionID @3
 - 属性laneID @10
- 测试无区域数据情况
 - 属性executionType @kanban
 - 属性regionID @0
- 测试无泳道数据情况
 - 属性executionType @kanban
 - 属性regionID @99
 - 属性laneID @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('project')->loadYaml('assignkanbanvars/project', false, 2)->gen(10);
zenData('kanbanregion')->loadYaml('assignkanbanvars/kanbanregion', false, 2)->gen(20);
zenData('kanbanlane')->loadYaml('assignkanbanvars/kanbanlane', false, 2)->gen(30);
zenData('kanbangroup')->loadYaml('assignkanbanvars/kanbangroup', false, 2)->gen(10);

su('admin');

$bugTest = new bugZenTest();

$execution1 = new stdclass();
$execution1->id = 1;
$execution1->type = 'kanban';

$execution2 = new stdclass();
$execution2->id = 2;
$execution2->type = 'sprint';

$execution3 = new stdclass();
$execution3->id = 3;
$execution3->type = 'stage';

$execution4 = new stdclass();
$execution4->id = 4;
$execution4->type = 'kanban';

$execution5 = new stdclass();
$execution5->id = 5;
$execution5->type = 'sprint';

$execution99 = new stdclass();
$execution99->id = 99;
$execution99->type = 'kanban';

r($bugTest->assignKanbanVarsTest($execution1, array())) && p('executionType') && e('kanban'); // 测试看板类型执行无output参数情况
r($bugTest->assignKanbanVarsTest($execution2, array())) && p('executionType') && e('sprint'); // 测试sprint类型执行无output参数情况
r($bugTest->assignKanbanVarsTest($execution3, array('regionID' => 5))) && p('executionType,regionID') && e('stage,5'); // 测试stage类型执行有regionID参数情况
r($bugTest->assignKanbanVarsTest($execution4, array('groupID' => 2))) && p('executionType') && e('kanban'); // 测试有groupID参数情况
r($bugTest->assignKanbanVarsTest($execution5, array('regionID' => 3, 'laneID' => 10))) && p('executionType,regionID,laneID') && e('sprint,3,10'); // 测试有regionID和laneID参数情况
r($bugTest->assignKanbanVarsTest($execution99, array())) && p('executionType,regionID') && e('kanban,0'); // 测试无区域数据情况
r($bugTest->assignKanbanVarsTest($execution1, array('regionID' => 99))) && p('executionType,regionID,laneID') && e('kanban,99,0'); // 测试无泳道数据情况