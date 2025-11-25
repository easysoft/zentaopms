#!/usr/bin/env php
<?php
/**

title=测试 buildModel->getExecutionBuilds();
timeout=0
cid=15493

- 全部执行版本查询
 - 第17条的execution属性 @125
 - 第17条的name属性 @版本17
- 单独执行版本查询
 - 第15条的execution属性 @107
 - 第15条的name属性 @版本15
- 不存在执行版本查询 @0
- 根据产品查询版本
 - 第19条的execution属性 @101
 - 第19条的name属性 @版本19
- 根据查询条件查询版本属性17 @0
- 无查询条件查询版本
 - 第17条的execution属性 @125
 - 第17条的name属性 @版本17
- 全部执行版本查询统计 @20
- 单独执行版本查询统计 @2
- 无查询条件查询版本统计 @20

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/build.unittest.class.php';

zenData('build')->loadYaml('build')->gen(20);
zenData('project')->loadYaml('execution')->gen(30);
zenData('product')->loadYaml('product')->gen(10);

su('admin');

$count           = array(0, 1);
$executionIDList = array(0, 107, 507);
$type            = array('all', 'product', 'bysearch', 'test');
$parm            = array(7, "t1.name = '执行版本版本17'", "test");

$build = new buildTest();
r($build->getExecutionBuildsTest($count[0], $executionIDList[0], $type[0]))           && p('17:execution,name') && e('125,版本17'); // 全部执行版本查询
r($build->getExecutionBuildsTest($count[0], $executionIDList[1], $type[0]))           && p('15:execution,name') && e('107,版本15'); // 单独执行版本查询
r($build->getExecutionBuildsTest($count[0], $executionIDList[2], $type[0]))           && p()                    && e('0');          // 不存在执行版本查询
r($build->getExecutionBuildsTest($count[0], $executionIDList[0], $type[1], $parm[0])) && p('19:execution,name') && e('101,版本19'); // 根据产品查询版本
r($build->getExecutionBuildsTest($count[0], $executionIDList[1], $type[2], $parm[1])) && p('17')                && e('0');          // 根据查询条件查询版本
r($build->getExecutionBuildsTest($count[0], $executionIDList[0], $type[3], $parm[2])) && p('17:execution,name') && e('125,版本17'); // 无查询条件查询版本
r($build->getExecutionBuildsTest($count[1], $executionIDList[0], $type[0]))           && p()                    && e('20');         // 全部执行版本查询统计
r($build->getExecutionBuildsTest($count[1], $executionIDList[1], $type[0]))           && p()                    && e('2');          // 单独执行版本查询统计
r($build->getExecutionBuildsTest($count[1], $executionIDList[0], $type[3], $parm[2])) && p()                    && e('20');         // 无查询条件查询版本统计
