#!/usr/bin/env php
<?php

/**

title=测试 bugZen::buildSearchFormForLinkBugs();
timeout=0
cid=0

- 步骤1：普通产品项目
 - 属性hasProductField @1
 - 属性hasExecutionField @1
 - 属性hasPlanField @1
- 步骤2：无产品项目
 - 属性hasProductField @0
 - 属性hasExecutionField @1
 - 属性hasPlanField @1
- 步骤3：无产品单迭代项目
 - 属性hasProductField @0
 - 属性hasExecutionField @0
 - 属性hasPlanField @0
- 步骤4：无产品多迭代项目
 - 属性hasProductField @0
 - 属性hasExecutionField @1
 - 属性hasPlanField @1
- 步骤5：有产品项目
 - 属性hasProductField @1
 - 属性hasExecutionField @1
 - 属性hasPlanField @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. zendata数据准备（根据需要配置）
$product = zenData('product');
$product->id->range('1-5');
$product->name->range('Product1,Product2,Product3,Product4,Product5');
$product->type->range('normal{5}');
$product->deleted->range('0{5}');
$product->gen(5);

$project = zenData('project');
$project->id->range('1-10');
$project->name->range('Project1,Project2,Project3,Project4,Project5,Project6,Project7,Project8,Project9,Project10');
$project->type->range('project{10}');
$project->model->range('scrum{5},waterfall{5}');
$project->hasProduct->range('1,1,0,0,0,1,1,1,1,1');
$project->multiple->range('1,1,1,0,1,1,1,1,1,1');
$project->deleted->range('0{10}');
$project->gen(10);

$bug = zenData('bug');
$bug->id->range('1-10');
$bug->product->range('1-5');
$bug->project->range('1,2,3,4,5,6,7,8,9,10');
$bug->execution->range('0{10}');
$bug->title->range('Bug1,Bug2,Bug3,Bug4,Bug5,Bug6,Bug7,Bug8,Bug9,Bug10');
$bug->status->range('active{10}');
$bug->deleted->range('0{10}');
$bug->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$bugTest = new bugZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($bugTest->buildSearchFormForLinkBugsTest((object)array('id' => 1, 'product' => 1, 'project' => 1), '', 0)) && p('hasProductField,hasExecutionField,hasPlanField') && e('1,1,1'); // 步骤1：普通产品项目
r($bugTest->buildSearchFormForLinkBugsTest((object)array('id' => 3, 'product' => 3, 'project' => 3), '1,2', 0)) && p('hasProductField,hasExecutionField,hasPlanField') && e('0,1,1'); // 步骤2：无产品项目
r($bugTest->buildSearchFormForLinkBugsTest((object)array('id' => 4, 'product' => 4, 'project' => 4), '1,2,3', 1)) && p('hasProductField,hasExecutionField,hasPlanField') && e('0,0,0'); // 步骤3：无产品单迭代项目
r($bugTest->buildSearchFormForLinkBugsTest((object)array('id' => 5, 'product' => 5, 'project' => 5), '', 2)) && p('hasProductField,hasExecutionField,hasPlanField') && e('0,1,1'); // 步骤4：无产品多迭代项目
r($bugTest->buildSearchFormForLinkBugsTest((object)array('id' => 6, 'product' => 1, 'project' => 6), '1', 3)) && p('hasProductField,hasExecutionField,hasPlanField') && e('1,1,1'); // 步骤5：有产品项目