#!/usr/bin/env php
<?php
/**

title=测试 buildModel->getProjectBuilds();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/build.class.php';

zdTable('build')->config('build')->gen(20);
zdTable('project')->config('execution')->gen(30);
zdTable('product')->config('product')->gen(10);
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
