#!/usr/bin/env php
<?php
/**

title=测试 buildModel->getProjectBuilds();
timeout=0
cid=15496

- 全部项目版本查询
 - 第1条的project属性 @11
 - 第1条的name属性 @版本1
- 单独项目版本查询属性7 @0
- 不存在项目版本查询 @0
- 根据产品查询版本
 - 第19条的project属性 @61
 - 第19条的name属性 @版本19
- 根据查询条件查询版本属性7 @0
- 无查询条件查询版本
 - 第7条的project属性 @61
 - 第7条的name属性 @版本7
- 全部项目版本查询统计 @20
- 单独项目版本查询统计 @0
- 无查询条件查询版本统计 @20

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/build.unittest.class.php';

zenData('build')->loadYaml('build')->gen(20);
zenData('project')->loadYaml('execution')->gen(30);
zenData('product')->loadYaml('product')->gen(10);
su('admin');

$count         = array(0, 1);
$projectIDList = array(0, 17, 7);
$type          = array('all', 'product', 'bysearch', 'test');
$parm          = array(7, "t1.name = '版本7'", "test");

$build = new buildTest();
r($build->getProjectBuildsTest($count[0], $projectIDList[0], $type[0]))           && p('1:project,name')  && e('11,版本1');  // 全部项目版本查询
r($build->getProjectBuildsTest($count[0], $projectIDList[1], $type[0]))           && p('7')               && e('0');         // 单独项目版本查询
r($build->getProjectBuildsTest($count[0], $projectIDList[2], $type[0]))           && p()                  && e('0');         // 不存在项目版本查询
r($build->getProjectBuildsTest($count[0], $projectIDList[0], $type[1], $parm[0])) && p('19:project,name') && e('61,版本19'); // 根据产品查询版本
r($build->getProjectBuildsTest($count[0], $projectIDList[1], $type[2], $parm[1])) && p('7')               && e('0');         // 根据查询条件查询版本
r($build->getProjectBuildsTest($count[0], $projectIDList[0], $type[3], $parm[2])) && p('7:project,name')  && e('61,版本7');  // 无查询条件查询版本
r($build->getProjectBuildsTest($count[1], $projectIDList[0], $type[0]))           && p()                  && e('20');        // 全部项目版本查询统计
r($build->getProjectBuildsTest($count[1], $projectIDList[1], $type[0]))           && p()                  && e('0');         // 单独项目版本查询统计
r($build->getProjectBuildsTest($count[1], $projectIDList[0], $type[3], $parm[2])) && p()                  && e('20');        // 无查询条件查询版本统计
