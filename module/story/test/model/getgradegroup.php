#!/usr/bin/env php
<?php

/**

title=测试 storyModel::getGradeGroup();
timeout=0
cid=18533

- 步骤1：正常获取等级分组数据 @3
- 步骤2：测试story类型数据 @3
- 步骤3：测试requirement类型数据 @3
- 步骤4：测试epic类型数据 @3
- 步骤5：测试数据结构正确性 @低

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$table = zenData('storygrade');
$table->type->range('story{3},requirement{3},epic{3}');
$table->grade->range('1-3,1-3,1-3');
$table->name->range('低,中,高,低,中,高,低,中,高');
$table->status->range('enable');
$table->gen(9);

su('admin');

$storyTest = new storyModelTest();

r(count($storyTest->getGradeGroupTest())) && p() && e('3'); // 步骤1：正常获取等级分组数据
r(count($storyTest->getGradeGroupTest()['story'])) && p() && e('3'); // 步骤2：测试story类型数据
r(count($storyTest->getGradeGroupTest()['requirement'])) && p() && e('3'); // 步骤3：测试requirement类型数据
r(count($storyTest->getGradeGroupTest()['epic'])) && p() && e('3'); // 步骤4：测试epic类型数据
r($storyTest->getGradeGroupTest()['story'][1]->name) && p() && e('低'); // 步骤5：测试数据结构正确性