#!/usr/bin/env php
<?php

/**

title=测试 bugZen::buildEditForm();
timeout=0
cid=0

- 步骤1:正常情况下编辑bug,验证基本视图属性
 - 属性bug @1
 - 属性product @1
 - 属性moduleOptionMenu @5
- 步骤2:编辑有项目和执行的bug,验证项目和执行数据
 - 属性projects @1
 - 属性executions @1
- 步骤3:编辑有分支的产品bug,验证分支选项属性branchTagOption @0
- 步骤4:编辑有重复bug的情况,验证duplicateBugs数量属性duplicateBugs @0
- 步骤5:验证编辑表单的projectID属性projectID @1
- 步骤6:无项目的bug,验证项目数据为空
 - 属性projectID @0
 - 属性projects @0
- 步骤7:有项目无执行的bug,验证执行数据为空
 - 属性projectID @1
 - 属性executions @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('product')->gen(5);
zenData('project')->gen(5);
zenData('bug')->gen(15);
zenData('build')->gen(5);
zenData('story')->gen(5);
zenData('task')->gen(5);
zenData('case')->gen(5);
zenData('user')->gen(10);
zenData('module')->gen(5);
zenData('branch')->gen(3);
zenData('productplan')->gen(5);
zenData('testtask')->gen(3);
zenData('action')->gen(10);

su('admin');

$bugTest = new bugZenTest();

$bug1 = (object)array(
    'id' => 1,
    'product' => 1,
    'branch' => 0,
    'project' => 1,
    'execution' => 1,
    'module' => 1,
    'title' => 'Test Bug 1',
    'type' => 'codeerror',
    'story' => 1,
    'storyTitle' => 'Story 1',
    'plan' => 1,
    'openedBuild' => '1,2',
    'assignedTo' => 'admin',
    'resolvedBy' => '',
    'closedBy' => '',
    'openedBy' => 'admin',
    'status' => 'active',
    'testtask' => 1
);

$bug2 = (object)array(
    'id' => 2,
    'product' => 2,
    'branch' => 0,
    'project' => 2,
    'execution' => 2,
    'module' => 2,
    'title' => 'Test Bug 2',
    'type' => 'codeerror',
    'story' => 2,
    'storyTitle' => 'Story 2',
    'plan' => 2,
    'openedBuild' => '3',
    'assignedTo' => 'user1',
    'resolvedBy' => '',
    'closedBy' => '',
    'openedBy' => 'admin',
    'status' => 'active',
    'testtask' => 2
);

$bug3 = (object)array(
    'id' => 3,
    'product' => 1,
    'branch' => 1,
    'project' => 1,
    'execution' => 1,
    'module' => 1,
    'title' => 'Test Bug 3 with Branch',
    'type' => 'codeerror',
    'story' => 1,
    'storyTitle' => 'Story 1',
    'plan' => 1,
    'openedBuild' => '1',
    'assignedTo' => 'admin',
    'resolvedBy' => '',
    'closedBy' => '',
    'openedBy' => 'admin',
    'status' => 'active',
    'testtask' => 1
);

$bug4 = (object)array(
    'id' => 4,
    'product' => 1,
    'branch' => 0,
    'project' => 0,
    'execution' => 0,
    'module' => 1,
    'title' => 'Test Bug 4 No Project',
    'type' => 'codeerror',
    'story' => 1,
    'storyTitle' => 'Story 1',
    'plan' => 1,
    'openedBuild' => '1',
    'assignedTo' => 'admin',
    'resolvedBy' => '',
    'closedBy' => '',
    'openedBy' => 'admin',
    'status' => 'active',
    'testtask' => 1
);

$bug5 = (object)array(
    'id' => 5,
    'product' => 1,
    'branch' => 0,
    'project' => 1,
    'execution' => 0,
    'module' => 1,
    'title' => 'Test Bug 5 with Project Only',
    'type' => 'codeerror',
    'story' => 1,
    'storyTitle' => 'Story 1',
    'plan' => 1,
    'openedBuild' => '1',
    'assignedTo' => 'admin',
    'resolvedBy' => '',
    'closedBy' => '',
    'openedBy' => 'admin',
    'status' => 'active',
    'testtask' => 1
);

r($bugTest->buildEditFormTest($bug1)) && p('bug,product,moduleOptionMenu') && e('1,1,5'); // 步骤1:正常情况下编辑bug,验证基本视图属性
r($bugTest->buildEditFormTest($bug2)) && p('projects,executions') && e('1,1'); // 步骤2:编辑有项目和执行的bug,验证项目和执行数据
r($bugTest->buildEditFormTest($bug3)) && p('branchTagOption') && e('0'); // 步骤3:编辑有分支的产品bug,验证分支选项
r($bugTest->buildEditFormTest($bug1)) && p('duplicateBugs') && e('0'); // 步骤4:编辑有重复bug的情况,验证duplicateBugs数量
r($bugTest->buildEditFormTest($bug1)) && p('projectID') && e('1'); // 步骤5:验证编辑表单的projectID
r($bugTest->buildEditFormTest($bug4)) && p('projectID,projects') && e('0,0'); // 步骤6:无项目的bug,验证项目数据为空
r($bugTest->buildEditFormTest($bug5)) && p('projectID,executions') && e('1,0'); // 步骤7:有项目无执行的bug,验证执行数据为空