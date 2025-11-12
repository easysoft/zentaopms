#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::buildCasesForBathcCreate();
timeout=0
cid=0

- 步骤1：正常批量创建用例，返回数组长度 @2
- 步骤2：检查第一个用例的产品ID第0条的product属性 @1
- 步骤3：检查第一个用例的状态第0条的status属性 @normal
- 步骤4：检查第一个用例的版本号第0条的version属性 @1
- 步骤5：项目模式下批量创建用例，返回数组长度 @2
- 步骤6：项目模式下检查第一个用例的项目ID第0条的project属性 @1
- 步骤7：检查第一个用例的创建者第0条的openedBy属性 @admin

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,产品3,产品4,产品5');
$product->gen(5);

$story = zenData('story');
$story->id->range('1-10');
$story->product->range('1');
$story->version->range('1-3');
$story->gen(10);

$project = zenData('project');
$project->id->range('1-5');
$project->name->range('项目1,项目2,项目3,项目4,项目5');
$project->type->range('project');
$project->multiple->range('0,1');
$project->parent->range('0');
$project->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcasezenTest();

// 准备正常的批量创建数据
$_POST = array(
    'title' => array('测试用例1', '测试用例2'),
    'type' => array('feature', 'feature'),
    'pri' => array(3, 3),
    'stage' => array('unittest', 'unittest'),
    'status' => array('normal', 'normal'),
    'precondition' => array('前置条件1', '前置条件2'),
    'keywords' => array('关键词1', '关键词2'),
    'steps' => array('步骤1', '步骤2'),
    'expects' => array('期望1', '期望2'),
    'story' => array(1, 2),
    'review' => array(0, 0)
);

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r(count($testcaseTest->buildCasesForBathcCreateTest(1, 'qa'))) && p() && e('2'); // 步骤1：正常批量创建用例，返回数组长度
r($result = $testcaseTest->buildCasesForBathcCreateTest(1, 'qa')) && p('0:product') && e('1'); // 步骤2：检查第一个用例的产品ID
r($result) && p('0:status') && e('normal'); // 步骤3：检查第一个用例的状态
r($result) && p('0:version') && e('1'); // 步骤4：检查第一个用例的版本号
r(count($testcaseTest->buildCasesForBathcCreateTest(1, 'project'))) && p() && e('2'); // 步骤5：项目模式下批量创建用例，返回数组长度
r($result = $testcaseTest->buildCasesForBathcCreateTest(1, 'project')) && p('0:project') && e('1'); // 步骤6：项目模式下检查第一个用例的项目ID
r($result) && p('0:openedBy') && e('admin'); // 步骤7：检查第一个用例的创建者