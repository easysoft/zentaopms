#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->getRegionPairs();
cid=1
pid=1

测试查询kanban1的区域信息 >> 默认区域
测试查询kanban1 reigon1的区域信息 >> 默认区域
测试查询kanban1 region2的区域信息 >> 0
测试查询kanban1的区域信息 >> 默认区域
测试查询kanban1 reigon2的区域信息 >> 默认区域
测试查询kanban1 region3的区域信息 >> 0
测试查询kanban1的区域信息 >> 默认区域
测试查询kanban1 reigon3的区域信息 >> 默认区域
测试查询kanban1 region4的区域信息 >> 0
测试查询kanban1的区域信息 >> 默认区域
测试查询kanban1 reigon4的区域信息 >> 默认区域
测试查询kanban1 region5的区域信息 >> 0
测试查询kanban1的区域信息 >> 默认区域
测试查询kanban1 reigon5的区域信息 >> 默认区域
测试查询kanban1 region10001的区域信息 >> 0
测试查询不存在的kanban的区域信息 >> 0
测试查询执行161的区域信息 >> 默认区域
测试查询执行162的区域信息 >> 默认区域

*/
$kanbanIDList    = array('1', '2', '3', '4', '5', '10001');
$regionIDList    = array('1', '2', '3', '4', '5', '10001');
$executionIDList = array('161', '162');

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
r($kanban->getRegionPairsTest($executionIDList[0], 0, 'execution')) && p('101') && e('默认区域'); // 测试查询执行161的区域信息
r($kanban->getRegionPairsTest($executionIDList[1], 0, 'execution')) && p('102') && e('默认区域'); // 测试查询执行162的区域信息