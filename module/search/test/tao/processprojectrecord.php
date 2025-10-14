#!/usr/bin/env php
<?php

/**

title=测试 searchTao::processProjectRecord();
timeout=0
cid=0

- 步骤1：普通项目模型返回view方法的URL @1
- 步骤2：kanban项目模型返回index方法的URL @1
- 步骤3：空项目模型返回view方法的URL @1
- 步骤4：测试URL中包含project模块 @1
- 步骤5：测试URL中包含正确的项目ID @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$project = zenData('project');
$project->id->range('1-10');
$project->name->range('项目{1-10}');
$project->model->range('waterfall{3},scrum{3},kanban{2},[]');
$project->status->range('wait{2},doing{4},suspended{2},closed{2}');
$project->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$searchTest = new searchTest();

// 5. 强制要求：必须包含至少5个测试步骤
$result1 = $searchTest->processProjectRecordTest((object)array('objectType' => 'project', 'objectID' => 1), array('project' => array(1 => (object)array('model' => 'waterfall'))));
r(strpos($result1->url, 'f=view') !== false) && p() && e('1'); // 步骤1：普通项目模型返回view方法的URL

$result2 = $searchTest->processProjectRecordTest((object)array('objectType' => 'project', 'objectID' => 2), array('project' => array(2 => (object)array('model' => 'kanban'))));
r(strpos($result2->url, 'f=index') !== false) && p() && e('1'); // 步骤2：kanban项目模型返回index方法的URL

$result3 = $searchTest->processProjectRecordTest((object)array('objectType' => 'project', 'objectID' => 3), array('project' => array(3 => (object)array('model' => ''))));
r(strpos($result3->url, 'f=view') !== false) && p() && e('1'); // 步骤3：空项目模型返回view方法的URL

$result4 = $searchTest->processProjectRecordTest((object)array('objectType' => 'project', 'objectID' => 4), array('project' => array(4 => (object)array('model' => 'waterfall'))));
r(strpos($result4->url, 'm=project') !== false) && p() && e('1'); // 步骤4：测试URL中包含project模块

$result5 = $searchTest->processProjectRecordTest((object)array('objectType' => 'project', 'objectID' => 5), array('project' => array(5 => (object)array('model' => 'scrum'))));
r(strpos($result5->url, 'id=5') !== false) && p() && e('1'); // 步骤5：测试URL中包含正确的项目ID