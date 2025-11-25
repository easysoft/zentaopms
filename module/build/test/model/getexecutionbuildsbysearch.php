#!/usr/bin/env php
<?php
/**

title=测试 buildModel->getExecutionBuildsBySearch();
timeout=0
cid=15494

- 执行id为0查询
 - 第20条的execution属性 @106
 - 第20条的name属性 @版本20
- 正常执行查询
 - 第19条的execution属性 @101
 - 第19条的name属性 @版本19
- 执行id为0查询统计 @20
- 正常执行查询统计 @3
- 查询条件查询
 - 第17条的execution属性 @125
 - 第17条的name属性 @版本17
- 查询条件查询统计 @20

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/build.unittest.class.php';

zenData('build')->loadYaml('build')->gen(20);
zenData('project')->loadYaml('execution')->gen(30);
zenData('product')->loadYaml('product')->gen(10);

su('admin');

$executionIDList = array(0, 101);
$queryIDList     = array(0, 5);
$count           = array(0, 1);

$build = new buildTest();

r($build->getExecutionBuildsBySearchTest($count[0], $executionIDList[0], $queryIDList[0])) && p('20:execution,name') && e('106,版本20'); // 执行id为0查询
r($build->getExecutionBuildsBySearchTest($count[0], $executionIDList[1], $queryIDList[0])) && p('19:execution,name') && e('101,版本19'); // 正常执行查询
r($build->getExecutionBuildsBySearchTest($count[1], $executionIDList[0], $queryIDList[0])) && p()                    && e('20');         // 执行id为0查询统计
r($build->getExecutionBuildsBySearchTest($count[1], $executionIDList[1], $queryIDList[0])) && p()                    && e('3');          // 正常执行查询统计
r($build->getExecutionBuildsBySearchTest($count[0], $executionIDList[0], $queryIDList[1])) && p('17:execution,name') && e('125,版本17'); // 查询条件查询
r($build->getExecutionBuildsBySearchTest($count[1], $executionIDList[0], $queryIDList[1])) && p()                    && e('20');         // 查询条件查询统计
