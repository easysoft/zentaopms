#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanbanregion')->gen(5);

/**

title=测试 kanbanModel->getRegionPairs();
timeout=0
cid=1

- 测试查询kanban1的区域信息属性1 @默认区域
- 测试查询kanban1 reigon1的区域信息属性1 @默认区域
- 测试查询kanban1 region2的区域信息属性1 @0
- 测试查询kanban1的区域信息属性2 @默认区域
- 测试查询kanban1 reigon2的区域信息属性2 @默认区域
- 测试查询kanban1 region3的区域信息属性2 @0
- 测试查询kanban1的区域信息属性3 @默认区域
- 测试查询kanban1 reigon3的区域信息属性3 @默认区域
- 测试查询kanban1 region4的区域信息属性3 @0
- 测试查询kanban1的区域信息属性4 @默认区域
- 测试查询kanban1 reigon4的区域信息属性4 @默认区域
- 测试查询kanban1 region5的区域信息属性4 @0
- 测试查询kanban1的区域信息属性5 @默认区域
- 测试查询kanban1 reigon5的区域信息属性5 @默认区域
- 测试查询kanban1 region10001的区域信息属性5 @0
- 测试查询不存在的kanban的区域信息 @0

*/
$kanbanIDList    = array('1', '2', '3', '4', '5', '10001');
$regionIDList    = array('1', '2', '3', '4', '5', '10001');

$kanban = new kanbanTest();

r($kanban->getRegionPairsTest($kanbanIDList[0]))                    && p('1')   && e('默认区域'); // 测试查询kanban1的区域信息
r($kanban->getRegionPairsTest($kanbanIDList[0], $regionIDList[0]))  && p('1')   && e('默认区域'); // 测试查询kanban1 reigon1的区域信息
r($kanban->getRegionPairsTest($kanbanIDList[0], $regionIDList[1]))  && p('1')   && e('0');        // 测试查询kanban1 region2的区域信息
r($kanban->getRegionPairsTest($kanbanIDList[1]))                    && p('2')   && e('默认区域'); // 测试查询kanban1的区域信息
r($kanban->getRegionPairsTest($kanbanIDList[1], $regionIDList[1]))  && p('2')   && e('默认区域'); // 测试查询kanban1 reigon2的区域信息
r($kanban->getRegionPairsTest($kanbanIDList[1], $regionIDList[2]))  && p('2')   && e('0');        // 测试查询kanban1 region3的区域信息
r($kanban->getRegionPairsTest($kanbanIDList[2]))                    && p('3')   && e('默认区域'); // 测试查询kanban1的区域信息
r($kanban->getRegionPairsTest($kanbanIDList[2], $regionIDList[2]))  && p('3')   && e('默认区域'); // 测试查询kanban1 reigon3的区域信息
r($kanban->getRegionPairsTest($kanbanIDList[2], $regionIDList[3]))  && p('3')   && e('0');        // 测试查询kanban1 region4的区域信息
r($kanban->getRegionPairsTest($kanbanIDList[3]))                    && p('4')   && e('默认区域'); // 测试查询kanban1的区域信息
r($kanban->getRegionPairsTest($kanbanIDList[3], $regionIDList[3]))  && p('4')   && e('默认区域'); // 测试查询kanban1 reigon4的区域信息
r($kanban->getRegionPairsTest($kanbanIDList[3], $regionIDList[4]))  && p('4')   && e('0');        // 测试查询kanban1 region5的区域信息
r($kanban->getRegionPairsTest($kanbanIDList[4]))                    && p('5')   && e('默认区域'); // 测试查询kanban1的区域信息
r($kanban->getRegionPairsTest($kanbanIDList[4], $regionIDList[4]))  && p('5')   && e('默认区域'); // 测试查询kanban1 reigon5的区域信息
r($kanban->getRegionPairsTest($kanbanIDList[4], $regionIDList[5]))  && p('5')   && e('0');        // 测试查询kanban1 region10001的区域信息
r($kanban->getRegionPairsTest($kanbanIDList[5]))                    && p()      && e('0');        // 测试查询不存在的kanban的区域信息
r($kanban->getRegionPairsTest($kanbanIDList[0], 0, 'execution'))    && p()      && e('0');        // 测试查询执行1的区域信息
