#!/usr/bin/env php
<?php

/**

title=测试 buildModel::getStoryBuilds();
timeout=0
cid=15499

- 执行buildTest模块的getStoryBuildsTest方法，参数是1  @2
- 执行buildTest模块的getStoryBuildsTest方法  @0
- 执行buildTest模块的getStoryBuildsTest方法，参数是999  @0
- 执行buildTest模块的getStoryBuildsTest方法，参数是2  @2
- 执行buildTest模块的getStoryBuildsTest方法，参数是3  @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/build.unittest.class.php';

$build = zenData('build');
$build->id->range('1-15');
$build->name->range('Build_001,Build_002,Build_003,Build_004,Build_005,Build_006,Build_007,Build_008,Build_009,Build_010,Build_011,Build_012,Build_013,Build_014,Build_015');
$build->product->range('1-3');
$build->project->range('11-13');
$build->stories->range('1,2,3,1,4,5,2,6,7,3,8,9,1,10,11,5,12,13,``,``,1,2,14,15,123,124,125,``,``,``');
$build->deleted->range('0{12},1{3}');
$build->date->range('`2023-01-01`,`2023-02-01`,`2023-03-01`,`2023-04-01`,`2023-05-01`,`2023-06-01`,`2023-07-01`,`2023-08-01`,`2023-09-01`,`2023-10-01`,`2023-11-01`,`2023-12-01`');
$build->createdBy->range('admin,user,tester');
$build->createdDate->range('`' . date('Y-m-d H:i:s') . '`');
$build->gen(15);

su('admin');

$buildTest = new buildTest();

r(count($buildTest->getStoryBuildsTest(1))) && p() && e('2');
r(count($buildTest->getStoryBuildsTest(0))) && p() && e('0');
r(count($buildTest->getStoryBuildsTest(999))) && p() && e('0');
r(count($buildTest->getStoryBuildsTest(2))) && p() && e('2');
r(count($buildTest->getStoryBuildsTest(3))) && p() && e('2');