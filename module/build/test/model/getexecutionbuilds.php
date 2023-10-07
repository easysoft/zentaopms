#!/usr/bin/env php
<?php
/**

title=测试 buildModel->getExecutionBuilds();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/build.class.php';

zdTable('build')->config('build')->gen(20);
zdTable('project')->config('execution')->gen(30);
zdTable('product')->config('product')->gen(10);

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
