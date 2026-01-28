#!/usr/bin/env php
<?php
/**

title=测试 loadModel->syncParentData()
timeout=0
cid=17783

- 测试同步父阶段ID为0的数据给ID为0的子阶段 @0
- 测试同步父阶段ID为2的数据给ID为0的子阶段 @0
- 测试同步父阶段ID为0的数据给ID为3的子阶段 @0
- 测试同步父阶段ID为2的数据给ID为3的子阶段
 - 第4条的id属性 @9
 - 第4条的name属性 @任务9
- 测试同步父阶段ID为5的数据给ID为6的子阶段
 - 第4条的id属性 @10
 - 第4条的name属性 @任务10
- 测试同步父阶段ID为5的数据给ID为7的子阶段 @0

*/
include dirname(__FILE__, 5). '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('project')->loadYaml('project')->gen(8)->fixPath();
zenData('task')->loadYaml('task')->gen(10);
zenData('projectstory')->loadYaml('projectstory')->gen(10);
zenData('bug')->loadYaml('bug')->gen(10);
zenData('case')->loadYaml('case')->gen(10);
zenData('projectcase')->loadYaml('projectcase')->gen(10);
zenData('testtask')->loadYaml('testtask')->gen(10);
zenData('testreport')->loadYaml('testreport')->gen(10);
zenData('build')->loadYaml('build')->gen(10);
zenData('effort')->loadYaml('effort')->gen(10);
zenData('action')->loadYaml('action')->gen(10);
zenData('actionrecent')->loadYaml('actionrecent')->gen(10);
zenData('doclib')->loadYaml('doclib')->gen(10);
zenData('doc')->loadYaml('doc')->gen(10);
zenData('module')->loadYaml('module')->gen(10);
zenData('team')->loadYaml('team')->gen(5);

$executionIdList = array(0, 3, 6, 7);
$parentIdList    = array(0, 2, 5);

$programplanTester = new programPlanTest();
r($programplanTester->syncParentDataTest($executionIdList[0], $parentIdList[0])) && p()            && e('0');         // 测试同步父阶段ID为0的数据给ID为0的子阶段
r($programplanTester->syncParentDataTest($executionIdList[0], $parentIdList[1])) && p()            && e('0');         // 测试同步父阶段ID为2的数据给ID为0的子阶段
r($programplanTester->syncParentDataTest($executionIdList[1], $parentIdList[0])) && p()            && e('0');         // 测试同步父阶段ID为0的数据给ID为3的子阶段
r($programplanTester->syncParentDataTest($executionIdList[1], $parentIdList[1])) && p('4:id,name') && e('9,任务9');   // 测试同步父阶段ID为2的数据给ID为3的子阶段
r($programplanTester->syncParentDataTest($executionIdList[2], $parentIdList[2])) && p('4:id,name') && e('10,任务10'); // 测试同步父阶段ID为5的数据给ID为6的子阶段
r($programplanTester->syncParentDataTest($executionIdList[3], $parentIdList[2])) && p()            && e('0');         // 测试同步父阶段ID为5的数据给ID为7的子阶段
