#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanbanregion')->gen(10);
zdTable('kanbanlane')->config('rdkanbanlane')->gen(10);

/**

title=测试 kanbanModel->getLanePairsByRegion();
timeout=0
cid=1

- 测试获取区域101的泳道 @泳道1
- 测试获取区域101的bug泳道 @0
- 测试获取区域101的task泳道 @泳道1
- 测试获取区域101的story泳道 @0
- 测试获取区域102的泳道 @泳道2
- 测试获取区域102的bug泳道 @0
- 测试获取区域102的task泳道 @泳道2
- 测试获取区域102的story泳道 @0
- 测试获取区域103的泳道 @泳道3
- 测试获取区域103的bug泳道 @0
- 测试获取区域103的task泳道 @泳道3
- 测试获取区域103的story泳道 @0
- 测试获取区域104的泳道 @泳道4
- 测试获取区域104的bug泳道 @0
- 测试获取区域104的task泳道 @泳道4
- 测试获取区域104的story泳道 @0
- 测试获取区域105的泳道 @泳道5
- 测试获取区域105的bug泳道 @0
- 测试获取区域105的task泳道 @泳道5
- 测试获取区域105的story泳道 @0
- 测试获取不存在的区域的泳道 @0
- 测试获取不存在的区域的bug泳道 @0
- 测试获取不存在的区域的task泳道 @0
- 测试获取不存在的区域的story泳道 @0

*/

$regionIDList = array('1', '2', '3', '4', '5', '1000001');
$type         = array('bug', 'task', 'story');

$kanban = new kanbanTest();

r($kanban->getLanePairsByRegionTest($regionIDList[0]))           && p() && e('泳道1'); // 测试获取区域101的泳道
r($kanban->getLanePairsByRegionTest($regionIDList[0], $type[0])) && p() && e('0');     // 测试获取区域101的bug泳道
r($kanban->getLanePairsByRegionTest($regionIDList[0], $type[1])) && p() && e('泳道1'); // 测试获取区域101的task泳道
r($kanban->getLanePairsByRegionTest($regionIDList[0], $type[2])) && p() && e('0');     // 测试获取区域101的story泳道
r($kanban->getLanePairsByRegionTest($regionIDList[1]))           && p() && e('泳道2'); // 测试获取区域102的泳道
r($kanban->getLanePairsByRegionTest($regionIDList[1], $type[0])) && p() && e('0');     // 测试获取区域102的bug泳道
r($kanban->getLanePairsByRegionTest($regionIDList[1], $type[1])) && p() && e('泳道2'); // 测试获取区域102的task泳道
r($kanban->getLanePairsByRegionTest($regionIDList[1], $type[2])) && p() && e('0');     // 测试获取区域102的story泳道
r($kanban->getLanePairsByRegionTest($regionIDList[2]))           && p() && e('泳道3'); // 测试获取区域103的泳道
r($kanban->getLanePairsByRegionTest($regionIDList[2], $type[0])) && p() && e('0');     // 测试获取区域103的bug泳道
r($kanban->getLanePairsByRegionTest($regionIDList[2], $type[1])) && p() && e('泳道3'); // 测试获取区域103的task泳道
r($kanban->getLanePairsByRegionTest($regionIDList[2], $type[2])) && p() && e('0');     // 测试获取区域103的story泳道
r($kanban->getLanePairsByRegionTest($regionIDList[3]))           && p() && e('泳道4'); // 测试获取区域104的泳道
r($kanban->getLanePairsByRegionTest($regionIDList[3], $type[0])) && p() && e('0');     // 测试获取区域104的bug泳道
r($kanban->getLanePairsByRegionTest($regionIDList[3], $type[1])) && p() && e('泳道4'); // 测试获取区域104的task泳道
r($kanban->getLanePairsByRegionTest($regionIDList[3], $type[2])) && p() && e('0');     // 测试获取区域104的story泳道
r($kanban->getLanePairsByRegionTest($regionIDList[4]))           && p() && e('泳道5'); // 测试获取区域105的泳道
r($kanban->getLanePairsByRegionTest($regionIDList[4], $type[0])) && p() && e('0');     // 测试获取区域105的bug泳道
r($kanban->getLanePairsByRegionTest($regionIDList[4], $type[1])) && p() && e('泳道5'); // 测试获取区域105的task泳道
r($kanban->getLanePairsByRegionTest($regionIDList[4], $type[2])) && p() && e('0');     // 测试获取区域105的story泳道
r($kanban->getLanePairsByRegionTest($regionIDList[5]))           && p() && e('0');     // 测试获取不存在的区域的泳道
r($kanban->getLanePairsByRegionTest($regionIDList[5], $type[0])) && p() && e('0');     // 测试获取不存在的区域的bug泳道
r($kanban->getLanePairsByRegionTest($regionIDList[5], $type[1])) && p() && e('0');     // 测试获取不存在的区域的task泳道
r($kanban->getLanePairsByRegionTest($regionIDList[5], $type[2])) && p() && e('0');     // 测试获取不存在的区域的story泳道