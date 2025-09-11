#!/usr/bin/env php
<?php

/**

title=测试 actionTao::getLinkedExtra();
timeout=0
cid=0

- 执行actionTest模块的getLinkedExtraTest方法  @1
- 执行actionTest模块的getLinkedExtraTest方法  @1
- 执行actionTest模块的getLinkedExtraTest方法  @1
- 执行actionTest模块的getLinkedExtraTest方法  @1
- 执行actionTest模块的getLinkedExtraTest方法  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

$project = zenData('project');
$project->id->range('1-5');
$project->name->range('项目{5}');
$project->type->range('project{3},sprint{2}');
$project->multiple->range('1{5}');
$project->gen(5);

$productplan = zenData('productplan');
$productplan->id->range('1-3');
$productplan->title->range('计划{3}');
$productplan->gen(3);

$build = zenData('build');
$build->id->range('1-3');
$build->name->range('版本{3}');
$build->execution->range('1-3');
$build->gen(3);

su('admin');

$actionTest = new actionTest();

r($actionTest->getLinkedExtraTest((object)array('extra' => '1', 'objectType' => 'story', 'execution' => '1'), 'execution')) && p() && e('1');
r($actionTest->getLinkedExtraTest((object)array('extra' => '1', 'objectType' => 'story', 'project' => '1'), 'project')) && p() && e('1');
r($actionTest->getLinkedExtraTest((object)array('extra' => '1', 'objectType' => 'story'), 'productplan')) && p() && e('1');
r($actionTest->getLinkedExtraTest((object)array('extra' => '1', 'objectType' => 'story'), 'build')) && p() && e('1');
r($actionTest->getLinkedExtraTest((object)array('extra' => '1', 'objectType' => 'story'), 'invalidtype')) && p() && e('0');