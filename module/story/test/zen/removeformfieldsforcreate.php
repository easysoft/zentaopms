#!/usr/bin/env php
<?php

/**

title=测试 storyZen::removeFormFieldsForCreate();
timeout=0
cid=18703

- 执行storyTest模块的removeFormFieldsForCreateTest方法，参数是$baseFields, 'story', 1, 'story' 第product条的control属性 @select
- 执行storyTest模块的removeFormFieldsForCreateTest方法，参数是$baseFields, 'requirement', 1, 'story' 属性branches @~~
- 执行storyTest模块的removeFormFieldsForCreateTest方法，参数是$baseFields, 'epic', 1, 'story' 属性branches @~~
- 执行storyTest模块的removeFormFieldsForCreateTest方法，参数是$baseFields, 'story', 6, 'project' 第product条的control属性 @hidden
- 执行storyTest模块的removeFormFieldsForCreateTest方法，参数是$baseFields, 'story', 8, 'execution' 第product条的control属性 @hidden

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/storyzen.unittest.class.php';

$project = zenData('project');
$project->id->range('1-10');
$project->name->range('项目{1-10}');
$project->hasProduct->range('1{5},0{5}');
$project->model->range('scrum{3},waterfall{3},kanban{4}');
$project->multiple->range('1{5},0{5}');
$project->gen(10);

su('admin');

$storyTest = new storyZenTest();

$baseFields = array(
    'product' => array('control' => 'select', 'options' => array()),
    'branches' => array('control' => 'select', 'options' => array()),
    'modules' => array('control' => 'select', 'options' => array()),
    'plans' => array('control' => 'select', 'options' => array()),
    'plan' => array('control' => 'select', 'options' => array()),
    'reviewer' => array('control' => 'select', 'options' => array()),
    'assignedTo' => array('control' => 'select', 'options' => array())
);

r($storyTest->removeFormFieldsForCreateTest($baseFields, 'story', 1, 'story')) && p('product:control') && e('select');
r($storyTest->removeFormFieldsForCreateTest($baseFields, 'requirement', 1, 'story')) && p('branches') && e('~~');
r($storyTest->removeFormFieldsForCreateTest($baseFields, 'epic', 1, 'story')) && p('branches') && e('~~');
r($storyTest->removeFormFieldsForCreateTest($baseFields, 'story', 6, 'project')) && p('product:control') && e('hidden');
r($storyTest->removeFormFieldsForCreateTest($baseFields, 'story', 8, 'execution')) && p('product:control') && e('hidden');