#!/usr/bin/env php
<?php

/**

title=测试 programModel::getBudgetLeft();
timeout=0
cid=17681

- 查看项目集1的所有父项目集的预算剩余 @1000
- 查看项目集2的所有父项目集的预算剩余 @2000
- 查看项目集3的所有父项目集的预算剩余 @3000
- 查看项目集4的所有父项目集的预算剩余 @4000
- 查看项目集5的所有父项目集的预算剩余 @5000

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

$program = zenData('project');
$program->id->range('1,2,3,4,5');
$program->type->range('program');
$program->budget->range('1000,2000,3000,4000,5000');
$program->gen(5);

$programTester = new programModelTest();
r($programTester->getBudgetLeftTest(1)) && p() && e('1000');  // 查看项目集1的所有父项目集的预算剩余
r($programTester->getBudgetLeftTest(2)) && p() && e('2000');  // 查看项目集2的所有父项目集的预算剩余
r($programTester->getBudgetLeftTest(3)) && p() && e('3000');  // 查看项目集3的所有父项目集的预算剩余
r($programTester->getBudgetLeftTest(4)) && p() && e('4000');  // 查看项目集4的所有父项目集的预算剩余
r($programTester->getBudgetLeftTest(5)) && p() && e('5000');  // 查看项目集5的所有父项目集的预算剩余
