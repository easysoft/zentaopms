#!/usr/bin/env php
<?php

/**

title=测试 storyZen::hiddenFormFieldsForEdit();
timeout=0
cid=18698

- 测试步骤1第product条的className属性 @hidden
- 测试步骤2第product条的className属性 @hidden
- 测试步骤3第plan条的className属性 @hidden
- 测试步骤4
 - 第plan条的className属性 @hidden
 - 第product条的className属性 @hidden
- 测试步骤5第parent条的className属性 @~~
- 测试步骤6第parent条的className属性 @hidden
- 测试步骤7第product条的className属性 @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/storyzen.unittest.class.php';

$product = zenData('product');
$product->id->range('1-10');
$product->name->range('产品1,产品2,产品3,产品4,产品5,产品6,产品7,产品8,产品9,产品10');
$product->shadow->range('0{5},1,1,1,1,1');
$product->gen(10);

$project = zenData('project');
$project->id->range('1-10');
$project->name->range('项目1,项目2,项目3,项目4,项目5,项目6,项目7,项目8,项目9,项目10');
$project->type->range('project{10}');
$project->model->range('scrum{2},waterfall,scrum{2},kanban{5}');
$project->multiple->range('1,1,1,0{7}');
$project->gen(10);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1,2,3,4,5');
$projectProduct->product->range('6,7,8,9,10');
$projectProduct->gen(5);

$team = zenData('team');
$team->root->range('1-10');
$team->account->range('admin,user1,user2');
$team->gen(30);

su('admin');

$storyTest = new storyZenTest();

// 测试场景1: 影子产品,scrum项目,multiple=1 (产品6关联项目1)
$fields1 = array(
    'product'    => array('name' => 'product', 'className' => ''),
    'plan'       => array('name' => 'plan', 'className' => ''),
    'parent'     => array('name' => 'parent', 'className' => ''),
    'reviewer'   => array('name' => 'reviewer', 'options' => array()),
    'assignedTo' => array('name' => 'assignedTo', 'options' => array()),
);
$product1 = new stdClass();
$product1->id = 6;
$product1->shadow = 1;
r($storyTest->hiddenFormFieldsForEditTest($fields1, 'story', $product1)) && p('product:className') && e('hidden'); // 测试步骤1

// 测试场景2: 影子产品,scrum项目,multiple=1 (产品6关联项目1)
$fields2 = array(
    'product'    => array('name' => 'product', 'className' => ''),
    'plan'       => array('name' => 'plan', 'className' => ''),
    'parent'     => array('name' => 'parent', 'className' => ''),
    'reviewer'   => array('name' => 'reviewer', 'options' => array()),
    'assignedTo' => array('name' => 'assignedTo', 'options' => array()),
);
$product2 = new stdClass();
$product2->id = 6;
$product2->shadow = 1;
r($storyTest->hiddenFormFieldsForEditTest($fields2, 'story', $product2)) && p('product:className') && e('hidden'); // 测试步骤2

// 测试场景3: 影子产品,waterfall项目 (产品8关联项目3)
$fields3 = array(
    'product'    => array('name' => 'product', 'className' => ''),
    'plan'       => array('name' => 'plan', 'className' => ''),
    'parent'     => array('name' => 'parent', 'className' => ''),
    'reviewer'   => array('name' => 'reviewer', 'options' => array()),
    'assignedTo' => array('name' => 'assignedTo', 'options' => array()),
);
$product3 = new stdClass();
$product3->id = 8;
$product3->shadow = 1;
r($storyTest->hiddenFormFieldsForEditTest($fields3, 'story', $product3)) && p('plan:className') && e('hidden'); // 测试步骤3

// 测试场景4: 影子产品,scrum项目,multiple=0 (产品9关联项目4)
$fields4 = array(
    'product'    => array('name' => 'product', 'className' => ''),
    'plan'       => array('name' => 'plan', 'className' => ''),
    'parent'     => array('name' => 'parent', 'className' => ''),
    'reviewer'   => array('name' => 'reviewer', 'options' => array()),
    'assignedTo' => array('name' => 'assignedTo', 'options' => array()),
);
$product4 = new stdClass();
$product4->id = 9;
$product4->shadow = 1;
r($storyTest->hiddenFormFieldsForEditTest($fields4, 'story', $product4)) && p('plan:className;product:className') && e('hidden;hidden'); // 测试步骤4

// 测试场景5: epic类型,已配置showStoryGrade
global $tester;
$fields5 = array(
    'product'    => array('name' => 'product', 'className' => ''),
    'plan'       => array('name' => 'plan', 'className' => ''),
    'parent'     => array('name' => 'parent', 'className' => ''),
    'reviewer'   => array('name' => 'reviewer', 'options' => array()),
    'assignedTo' => array('name' => 'assignedTo', 'options' => array()),
);
$product5 = new stdClass();
$product5->id = 5;
$product5->shadow = 0;
$tester->config->showStoryGrade = '1';
r($storyTest->hiddenFormFieldsForEditTest($fields5, 'epic', $product5)) && p('parent:className') && e('~~'); // 测试步骤5

// 测试场景6: epic类型,未配置showStoryGrade
$fields6 = array(
    'product'    => array('name' => 'product', 'className' => ''),
    'plan'       => array('name' => 'plan', 'className' => ''),
    'parent'     => array('name' => 'parent', 'className' => ''),
    'reviewer'   => array('name' => 'reviewer', 'options' => array()),
    'assignedTo' => array('name' => 'assignedTo', 'options' => array()),
);
$product6 = new stdClass();
$product6->id = 1;
$product6->shadow = 0;
$tester->config->showStoryGrade = '';
r($storyTest->hiddenFormFieldsForEditTest($fields6, 'epic', $product6)) && p('parent:className') && e('hidden'); // 测试步骤6

// 测试场景7: 普通产品(非影子产品),story类型
$fields7 = array(
    'product'    => array('name' => 'product', 'className' => ''),
    'plan'       => array('name' => 'plan', 'className' => ''),
    'parent'     => array('name' => 'parent', 'className' => ''),
    'reviewer'   => array('name' => 'reviewer', 'options' => array()),
    'assignedTo' => array('name' => 'assignedTo', 'options' => array()),
);
$product7 = new stdClass();
$product7->id = 1;
$product7->shadow = 0;
r($storyTest->hiddenFormFieldsForEditTest($fields7, 'story', $product7)) && p('product:className') && e('~~'); // 测试步骤7