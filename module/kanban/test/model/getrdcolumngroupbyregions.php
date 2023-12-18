#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanbancolumn')->config('rdkanbancolumn')->gen(20);
zdTable('kanbanregion')->config('rdkanbanregion')->gen(20);
zdTable('kanbangroup')->config('rdkanbangroup')->gen(20);

/**

title=测试 kanbanModel->getRDColumnGroupByRegions();
timeout=0
cid=1

- 测试获取region 1 执行看板泳道列组 @4
- 测试获取region 1 group 1执行看板泳道列组 @4
- 测试获取region 1 group 2执行看板泳道列组 @0
- 测试获取region 2 执行看板泳道列组 @4
- 测试获取region 2 group 2执行看板泳道列组 @4
- 测试获取region 2 group 1执行看板泳道列组 @0
- 测试获取region 3 执行看板泳道列组 @8
- 测试获取region 3 group 3执行看板泳道列组 @4
- 测试获取region 3 group 1执行看板泳道列组 @0
- 测试获取region 4 执行看板泳道列组 @4
- 测试获取region 4 group 5,6执行看板泳道列组 @4
- 测试获取region 5 执行看板泳道列组 @0
- 测试获取region 5 group 8,10执行看板泳道列组 @0

*/

$regions     = array(array(1), array(2), array(3,4), array(5,6,7), array(8,9,10,11));
$groupIDList = array(array(1), array(2), array(3), array(5,6), array(8,10));

$kanban = new kanbanTest();

r($kanban->getRDColumnGroupByRegionsTest($regions[0]))                  && p() && e('4');  // 测试获取region 1 执行看板泳道列组
r($kanban->getRDColumnGroupByRegionsTest($regions[0], $groupIDList[0])) && p() && e('4');  // 测试获取region 1 group 1执行看板泳道列组
r($kanban->getRDColumnGroupByRegionsTest($regions[0], $groupIDList[1])) && p() && e('0');  // 测试获取region 1 group 2执行看板泳道列组
r($kanban->getRDColumnGroupByRegionsTest($regions[1]))                  && p() && e('4');  // 测试获取region 2 执行看板泳道列组
r($kanban->getRDColumnGroupByRegionsTest($regions[1], $groupIDList[1])) && p() && e('4');  // 测试获取region 2 group 2执行看板泳道列组
r($kanban->getRDColumnGroupByRegionsTest($regions[1], $groupIDList[0])) && p() && e('0');  // 测试获取region 2 group 1执行看板泳道列组
r($kanban->getRDColumnGroupByRegionsTest($regions[2]))                  && p() && e('8');  // 测试获取region 3 执行看板泳道列组
r($kanban->getRDColumnGroupByRegionsTest($regions[2], $groupIDList[2])) && p() && e('4');  // 测试获取region 3 group 3执行看板泳道列组
r($kanban->getRDColumnGroupByRegionsTest($regions[2], $groupIDList[0])) && p() && e('0');  // 测试获取region 3 group 1执行看板泳道列组
r($kanban->getRDColumnGroupByRegionsTest($regions[3]))                  && p() && e('4');  // 测试获取region 4 执行看板泳道列组
r($kanban->getRDColumnGroupByRegionsTest($regions[3], $groupIDList[3])) && p() && e('4');  // 测试获取region 4 group 5,6执行看板泳道列组
r($kanban->getRDColumnGroupByRegionsTest($regions[4]))                  && p() && e('0');  // 测试获取region 5 执行看板泳道列组
r($kanban->getRDColumnGroupByRegionsTest($regions[4], $groupIDList[4])) && p() && e('0');  // 测试获取region 5 group 8,10执行看板泳道列组