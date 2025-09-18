#!/usr/bin/env php
<?php

/**

title=测试 storyZen::removeFormFieldsForCreate();
timeout=0
cid=0

- 步骤1：测试story类型包含product字段第product条的control属性 @select
- 步骤2：测试requirement类型移除branches字段属性branches @~~
- 步骤3：测试epic类型包含product字段第product条的control属性 @select
- 步骤4：测试项目页面包含plan字段第plan条的control属性 @select
- 步骤5：测试execution页面包含assignedTo字段第assignedTo条的control属性 @select

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$project = zenData('project');
$project->id->range('1-10');
$project->name->range('项目{1-10}');
$project->hasProduct->range('1{5},0{5}');
$project->model->range('scrum{3},waterfall{3},kanban{4}');
$project->multiple->range('1{5},0{5}');
$project->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$storyTest = new storyTest();

// 模拟初始表单字段
$baseFields = array(
    'product' => array('control' => 'select', 'options' => array()),
    'branches' => array('control' => 'select', 'options' => array()),
    'modules' => array('control' => 'select', 'options' => array()),
    'plans' => array('control' => 'select', 'options' => array()),
    'plan' => array('control' => 'select', 'options' => array()),
    'reviewer' => array('control' => 'select', 'options' => array()),
    'assignedTo' => array('control' => 'select', 'options' => array())
);

// 5. 强制要求：必须包含至少5个测试步骤
r($storyTest->removeFormFieldsForCreateTest($baseFields, 'story', 1, 'story')) && p('product:control') && e('select'); // 步骤1：测试story类型包含product字段
r($storyTest->removeFormFieldsForCreateTest($baseFields, 'requirement', 1, 'story')) && p('branches') && e('~~'); // 步骤2：测试requirement类型移除branches字段
r($storyTest->removeFormFieldsForCreateTest($baseFields, 'epic', 1, 'story')) && p('product:control') && e('select'); // 步骤3：测试epic类型包含product字段
r($storyTest->removeFormFieldsForCreateTest($baseFields, 'story', 6, 'project')) && p('plan:control') && e('select'); // 步骤4：测试项目页面包含plan字段
r($storyTest->removeFormFieldsForCreateTest($baseFields, 'story', 7, 'execution')) && p('assignedTo:control') && e('select'); // 步骤5：测试execution页面包含assignedTo字段