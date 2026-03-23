#!/usr/bin/env php
<?php
/**

title=测试 bugZen::assignProjectRelatedVars();
timeout=0
cid=15427

- 获取项目相关变量
 - 属性noProductProjects @0
 - 属性noSprintProjects @0
 - 属性productProjects @0
 - 属性productExecutions @0
 - 属性productOpenedBuilds @1
 - 属性projectOpenedBuilds @1
 - 属性projectOpenedBuilds @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('product')->gen(5);
zenData('bug')->gen(5);
zenData('build')->gen(10);
zenData('user')->gen(5);

$projectTable = zenData('project');
$projectTable->type->range('project,scrum');
$projectTable->project->range('0,1');
$projectTable->gen(2)->fixPath();

su('admin');

$bugTest = new bugZenTest();

$bugs = array(
    (object)array('id' => 1, 'title' => 'Bug1', 'shadow' => 0 , 'product' => 1 , 'branch' => 0, 'project' => 0, 'execution' => 0),
    (object)array('id' => 2, 'title' => 'Bug2', 'shadow' => 0 , 'product' => 2 , 'branch' => 0, 'project' => 0, 'execution' => 2),
    (object)array('id' => 3, 'title' => 'Bug3', 'shadow' => 1 , 'product' => 3 , 'branch' => 0, 'project' => 1, 'execution' => 0)
);

$products = array(
    (object)array('id' => 1, 'name' => '产品1',     'shadow' => 0),
    (object)array('id' => 2, 'name' => '产品2',     'shadow' => 0),
    (object)array('id' => 3, 'name' => '影子产品3', 'shadow' => 1)
);

r($bugTest->assignProjectRelatedVarsTest($bugs, $products)) && p('noProductProjects,noSprintProjects,productProjects,productExecutions,productOpenedBuilds,projectOpenedBuilds,projectOpenedBuilds') && e('0,0,0,0,1,1,1'); // 获取项目相关变量
