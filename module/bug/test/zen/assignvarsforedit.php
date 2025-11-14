#!/usr/bin/env php
<?php

/**

title=测试 bugZen::assignVarsForEdit();
timeout=0
cid=15431

- 步骤1:bug有execution的情况属性execution @1
- 步骤2:bug有project但无execution的情况属性execution @0
- 步骤3:bug无project和execution的情况属性execution @0
- 步骤4:bug产品不在products列表中属性products @5
- 步骤5:bug有assignedTo的情况属性assignedToList @11
- 步骤6:bug状态为closed的情况属性assignedToList @11
- 步骤7:bug有story关联的情况属性stories @0
- 步骤8:验证openedBuilds属性openedBuilds @2
- 步骤9:验证cases数据属性cases @4

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
zenData('action')->gen(5);

su('admin');

$bugTest = new bugZenTest();

$product1 = (object)array('id' => 1, 'name' => '产品1', 'type' => 'normal', 'shadow' => 0, 'status' => 'normal');
$product2 = (object)array('id' => 2, 'name' => '产品2', 'type' => 'normal', 'shadow' => 0, 'status' => 'normal');
$product3 = (object)array('id' => 3, 'name' => '产品3', 'type' => 'normal', 'shadow' => 1, 'status' => 'normal');

$bug1 = (object)array('id' => 1, 'product' => 1, 'branch' => 0, 'project' => 1, 'execution' => 1, 'module' => 0, 'story' => 1, 'storyTitle' => '需求1', 'assignedTo' => 'admin', 'resolvedBy' => '', 'closedBy' => '', 'openedBy' => 'admin', 'status' => 'active', 'openedBuild' => '1', 'testtask' => 1);
$bug2 = (object)array('id' => 2, 'product' => 1, 'branch' => 0, 'project' => 1, 'execution' => 0, 'module' => 0, 'story' => 0, 'storyTitle' => '', 'assignedTo' => 'user1', 'resolvedBy' => '', 'closedBy' => '', 'openedBy' => 'admin', 'status' => 'active', 'openedBuild' => '', 'testtask' => 0);
$bug3 = (object)array('id' => 3, 'product' => 1, 'branch' => 0, 'project' => 0, 'execution' => 0, 'module' => 0, 'story' => 0, 'storyTitle' => '', 'assignedTo' => '', 'resolvedBy' => '', 'closedBy' => '', 'openedBy' => 'admin', 'status' => 'active', 'openedBuild' => '', 'testtask' => 0);
$bug4 = (object)array('id' => 4, 'product' => 2, 'branch' => 0, 'project' => 0, 'execution' => 0, 'module' => 0, 'story' => 0, 'storyTitle' => '', 'assignedTo' => '', 'resolvedBy' => '', 'closedBy' => '', 'openedBy' => 'admin', 'status' => 'active', 'openedBuild' => '', 'testtask' => 0);
$bug5 = (object)array('id' => 5, 'product' => 1, 'branch' => 0, 'project' => 0, 'execution' => 0, 'module' => 0, 'story' => 0, 'storyTitle' => '', 'assignedTo' => 'testuser', 'resolvedBy' => '', 'closedBy' => '', 'openedBy' => 'admin', 'status' => 'active', 'openedBuild' => '', 'testtask' => 0);
$bug6 = (object)array('id' => 6, 'product' => 1, 'branch' => 0, 'project' => 0, 'execution' => 0, 'module' => 0, 'story' => 0, 'storyTitle' => '', 'assignedTo' => 'closed', 'resolvedBy' => '', 'closedBy' => 'admin', 'openedBy' => 'admin', 'status' => 'closed', 'openedBuild' => '', 'testtask' => 0);

r($bugTest->assignVarsForEditTest($bug1, $product1)) && p('execution') && e('1'); // 步骤1:bug有execution的情况
r($bugTest->assignVarsForEditTest($bug2, $product1)) && p('execution') && e('0'); // 步骤2:bug有project但无execution的情况
r($bugTest->assignVarsForEditTest($bug3, $product1)) && p('execution') && e('0'); // 步骤3:bug无project和execution的情况
r($bugTest->assignVarsForEditTest($bug4, $product2)) && p('products') && e('5'); // 步骤4:bug产品不在products列表中
r($bugTest->assignVarsForEditTest($bug5, $product1)) && p('assignedToList') && e('11'); // 步骤5:bug有assignedTo的情况
r($bugTest->assignVarsForEditTest($bug6, $product1)) && p('assignedToList') && e('11'); // 步骤6:bug状态为closed的情况
r($bugTest->assignVarsForEditTest($bug1, $product1)) && p('stories') && e('0'); // 步骤7:bug有story关联的情况
r($bugTest->assignVarsForEditTest($bug1, $product1)) && p('openedBuilds') && e('2'); // 步骤8:验证openedBuilds
r($bugTest->assignVarsForEditTest($bug1, $product1)) && p('cases') && e('4'); // 步骤9:验证cases数据