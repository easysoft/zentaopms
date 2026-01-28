#!/usr/bin/env php
<?php

/**

title=测试 programplanModel::isTopStage();
timeout=0
cid=17753

- 测试父项目类型为project的阶段ID=3 @1
- 测试父项目类型为project的阶段ID=5 @1
- 测试父项目类型为stage的阶段ID=7 @0
- 测试不存在的阶段ID=999 @0
- 测试无效的阶段ID=0 @0
- 测试父项目类型为stage的阶段ID=8 @0
- 测试负数的阶段ID=-1 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);
su('admin');

$projectTable = zenData('project');
$projectTable->id->range('1-10');
$projectTable->type->range('program{1},project{1},stage{1},project{1},stage{3},sprint{1},stage{2}');
$projectTable->parent->range('0{1},0{1},2{1},0{1},4{1},2{1},3{1},6{1},7{1},8{1}');
$projectTable->name->range('项目集1,项目1,阶段1,项目2,阶段2,阶段3,阶段4,阶段5,阶段6,阶段7');
$projectTable->status->range('wait{2},doing{4},suspended{2},closed{2}');
$projectTable->deleted->range('0');
$projectTable->gen(10);

$programplanTest = new programplanModelTest();

r($programplanTest->isTopStageTest(3)) && p('') && e('1'); // 测试父项目类型为project的阶段ID=3
r($programplanTest->isTopStageTest(5)) && p('') && e('1'); // 测试父项目类型为project的阶段ID=5
r($programplanTest->isTopStageTest(7)) && p('') && e('0'); // 测试父项目类型为stage的阶段ID=7
r($programplanTest->isTopStageTest(999)) && p('') && e('0'); // 测试不存在的阶段ID=999
r($programplanTest->isTopStageTest(0)) && p('') && e('0'); // 测试无效的阶段ID=0
r($programplanTest->isTopStageTest(8)) && p('') && e('0'); // 测试父项目类型为stage的阶段ID=8
r($programplanTest->isTopStageTest(-1)) && p('') && e('0'); // 测试负数的阶段ID=-1