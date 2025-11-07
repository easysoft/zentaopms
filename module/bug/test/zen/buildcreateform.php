#!/usr/bin/env php
<?php

/**

title=测试 bugZen::buildCreateForm();
timeout=0
cid=0

- 步骤1:正常情况下验证产品成员数量属性productMembers @11
- 步骤2:from='global'的情况属性gobackLink @buildcreateform.php?m=bug&f=browse&productID=1
- 步骤3:有resultID和stepIdList的情况属性resultFilesCount @0
- 步骤4:验证产品和项目数据
 - 属性productsCount @5
 - 属性projectsCount @0
- 步骤5:验证执行和构建数据
 - 属性executionsCount @0
 - 属性buildsCount @2
- 步骤6:验证模块和分支数据
 - 属性moduleOptionMenuCount @4
 - 属性branchesCount @1
- 步骤7:验证计划和用例数据
 - 属性plansCount @3
 - 属性casesCount @4

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('product')->gen(5);
zenData('project')->gen(5);
zenData('bug')->gen(10);
zenData('build')->gen(5);
zenData('story')->gen(5);
zenData('task')->gen(5);
zenData('case')->gen(5);
zenData('user')->gen(10);
zenData('module')->gen(5);
zenData('branch')->gen(3);
zenData('productplan')->gen(5);

su('admin');

$bugTest = new bugZenTest();

$bug1 = (object)array(
    'id' => 0,
    'productID' => 1,
    'product' => 1,
    'branch' => '0',
    'project' => 1,
    'projectID' => 1,
    'execution' => 1,
    'executionID' => 1,
    'module' => 0,
    'moduleID' => 0,
    'assignedTo' => 'admin',
    'openedBy' => 'admin',
    'status' => 'active'
);

$bug2 = (object)array(
    'id' => 0,
    'productID' => 2,
    'product' => 2,
    'branch' => '0',
    'project' => 2,
    'projectID' => 2,
    'execution' => 2,
    'executionID' => 2,
    'module' => 0,
    'moduleID' => 0,
    'assignedTo' => 'user1',
    'openedBy' => 'admin',
    'status' => 'active'
);

$bug3 = (object)array(
    'id' => 0,
    'productID' => 1,
    'product' => 1,
    'branch' => '1',
    'project' => 1,
    'projectID' => 1,
    'execution' => 1,
    'executionID' => 1,
    'module' => 1,
    'moduleID' => 1,
    'assignedTo' => 'admin',
    'openedBy' => 'admin',
    'status' => 'active'
);

$param1 = array('resultID' => 0, 'stepIdList' => '', 'executionID' => 1, 'bugID' => 0);
$param2 = array('resultID' => 1, 'stepIdList' => '1_2_3', 'executionID' => 2, 'bugID' => 0);
$param3 = array('resultID' => 0, 'stepIdList' => '', 'executionID' => 1, 'bugID' => 0);

r($bugTest->buildCreateFormTest($bug1, $param1, '')) && p('productMembers') && e('11'); // 步骤1:正常情况下验证产品成员数量
r($bugTest->buildCreateFormTest($bug1, $param1, 'global')) && p('gobackLink') && e('buildcreateform.php?m=bug&f=browse&productID=1'); // 步骤2:from='global'的情况
r($bugTest->buildCreateFormTest($bug2, $param2, '')) && p('resultFilesCount') && e('0'); // 步骤3:有resultID和stepIdList的情况
r($bugTest->buildCreateFormTest($bug1, $param1, '')) && p('productsCount,projectsCount') && e('5,0'); // 步骤4:验证产品和项目数据
r($bugTest->buildCreateFormTest($bug1, $param1, '')) && p('executionsCount,buildsCount') && e('0,2'); // 步骤5:验证执行和构建数据
r($bugTest->buildCreateFormTest($bug3, $param3, '')) && p('moduleOptionMenuCount,branchesCount') && e('4,1'); // 步骤6:验证模块和分支数据
r($bugTest->buildCreateFormTest($bug1, $param1, '')) && p('plansCount,casesCount') && e('3,4'); // 步骤7:验证计划和用例数据