#!/usr/bin/env php
<?php

/**

title=测试 projectreleaseZen::buildReleaseForCreate();
timeout=0
cid=0

- 执行projectreleaseTest模块的buildReleaseForCreateTest方法，参数是1, array  @object
- 执行projectreleaseTest模块的buildReleaseForCreateTest方法，参数是0, array  @object
- 执行projectreleaseTest模块的buildReleaseForCreateTest方法，参数是1, array  @object
- 执行projectreleaseTest模块的buildReleaseForCreateTest方法，参数是1, array  @object
- 执行projectreleaseTest模块的buildReleaseForCreateTest方法，参数是1, array  @object

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/projectrelease.unittest.class.php';

$project = zenData('project');
$project->id->range('1-5');
$project->name->range('项目{1-5}');
$project->type->range('project');
$project->status->range('wait');
$project->gen(5);

$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品{1-5}');
$product->type->range('normal');
$product->status->range('normal');
$product->gen(5);

$build = zenData('build');
$build->id->range('1-10');
$build->name->range('版本{1-10}');
$build->product->range('1-5');
$build->project->range('1-5');
$build->gen(10);

su('admin');

$projectreleaseTest = new projectreleaseTest();

r($projectreleaseTest->buildReleaseForCreateTest(1, array('name' => 'Test Release', 'status' => 'wait', 'product' => 1))) && p('') && e('object');
r($projectreleaseTest->buildReleaseForCreateTest(0, array('name' => 'Test Release', 'status' => 'wait'))) && p('') && e('object');
r($projectreleaseTest->buildReleaseForCreateTest(1, array('name' => 'Test Release', 'status' => 'normal', 'product' => 1))) && p('') && e('object');
r($projectreleaseTest->buildReleaseForCreateTest(1, array())) && p('') && e('object');
r($projectreleaseTest->buildReleaseForCreateTest(1, array('name' => 'Test Release', 'status' => 'wait', 'product' => 1, 'build' => array(1)))) && p('') && e('object');