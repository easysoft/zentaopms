#!/usr/bin/env php
<?php

/**

title=测试 bugZen::assignProjectRelatedVars();
timeout=0
cid=15428

- 步骤1:空输入
 - 属性noProductProjects @0
 - 属性productProjects @0
 - 属性productExecutions @0
- 步骤2:无项目执行的bug
 - 属性noProductProjects @0
 - 属性noSprintProjects @0
- 步骤3:有项目的bug
 - 属性noProductProjects @0
 - 属性noSprintProjects @0
- 步骤4:有项目和执行的bug
 - 属性noProductProjects @0
 - 属性noSprintProjects @0
- 步骤5:多产品bugs
 - 属性noProductProjects @0
 - 属性noSprintProjects @0
- 步骤6:影子产品
 - 属性noProductProjects @0
 - 属性noSprintProjects @0
- 步骤7:混合产品
 - 属性noProductProjects @0
 - 属性noSprintProjects @0
- 步骤8:同产品多bugs属性productProjects @1
- 步骤9:验证projectExecutions属性projectExecutions @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('product')->loadYaml('product', false, 2)->gen(10);
zenData('project')->gen(20);
zenData('build')->gen(20);
zenData('projectproduct')->gen(30);
zenData('bug')->gen(20);

su('admin');

$bugTest = new bugZenTest();

$emptyBugs = array();
$emptyProducts = array();

$product1 = (object)array('id' => 1, 'name' => '产品1', 'type' => 'normal', 'shadow' => 0);
$product2 = (object)array('id' => 2, 'name' => '产品2', 'type' => 'normal', 'shadow' => 0);
$product3 = (object)array('id' => 3, 'name' => '产品3', 'type' => 'normal', 'shadow' => 1);

$bug1 = (object)array('id' => 1, 'product' => 1, 'branch' => 0, 'project' => 0, 'execution' => 0);
$bug2 = (object)array('id' => 2, 'product' => 1, 'branch' => 0, 'project' => 1, 'execution' => 0);
$bug3 = (object)array('id' => 3, 'product' => 1, 'branch' => 0, 'project' => 1, 'execution' => 1);
$bug4 = (object)array('id' => 4, 'product' => 2, 'branch' => 0, 'project' => 0, 'execution' => 0);
$bug5 = (object)array('id' => 5, 'product' => 3, 'branch' => 0, 'project' => 0, 'execution' => 0);

r($bugTest->assignProjectRelatedVarsTest($emptyBugs, $emptyProducts)) && p('noProductProjects,productProjects,productExecutions') && e('0,0,0'); // 步骤1:空输入
r($bugTest->assignProjectRelatedVarsTest(array($bug1), array(1 => $product1))) && p('noProductProjects,noSprintProjects') && e('0,0'); // 步骤2:无项目执行的bug
r($bugTest->assignProjectRelatedVarsTest(array($bug2), array(1 => $product1))) && p('noProductProjects,noSprintProjects') && e('0,0'); // 步骤3:有项目的bug
r($bugTest->assignProjectRelatedVarsTest(array($bug3), array(1 => $product1))) && p('noProductProjects,noSprintProjects') && e('0,0'); // 步骤4:有项目和执行的bug
r($bugTest->assignProjectRelatedVarsTest(array($bug1, $bug4), array(1 => $product1, 2 => $product2))) && p('noProductProjects,noSprintProjects') && e('0,0'); // 步骤5:多产品bugs
r($bugTest->assignProjectRelatedVarsTest(array($bug5), array(3 => $product3))) && p('noProductProjects,noSprintProjects') && e('0,0'); // 步骤6:影子产品
r($bugTest->assignProjectRelatedVarsTest(array($bug1, $bug5), array(1 => $product1, 3 => $product3))) && p('noProductProjects,noSprintProjects') && e('0,0'); // 步骤7:混合产品
r($bugTest->assignProjectRelatedVarsTest(array($bug1, $bug2), array(1 => $product1))) && p('productProjects') && e('1'); // 步骤8:同产品多bugs
r($bugTest->assignProjectRelatedVarsTest(array($bug3), array(1 => $product1))) && p('projectExecutions') && e('0'); // 步骤9:验证projectExecutions