#!/usr/bin/env php
<?php

/**

title=测试 executionModel::getFullNameList();
timeout=0
cid=16315

- 执行executionTest模块的getFullNameListTest方法，参数是$emptyArray @0
- 执行executionTest模块的getFullNameListTest方法，参数是array 属性11 @敏捷项目
- 执行executionTest模块的getFullNameListTest方法，参数是array 属性101 @迭代项目/迭代1
- 执行executionTest模块的getFullNameListTest方法，参数是$mixedExecutions
 - 属性11 @敏捷项目
 - 属性101 @迭代项目/迭代1
 - 属性201 @迭代项目/迭代1/子迭代1
- 执行executionTest模块的getFullNameListTest方法，参数是$invalidPathExecution 属性999 @无效执行
- 执行executionTest模块的getFullNameListTest方法，参数是$largeExecutionSet @8

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';

zenData('user')->gen(5);
su('admin');

$project = zenData('project');
$project->id->range('11,60,61,100,101-108,200-205');
$project->name->range('敏捷项目,瀑布项目,看板项目,迭代项目,迭代1,迭代2,迭代3,迭代4,迭代5,迭代6,子迭代1,子迭代2,子迭代3,子迭代4,子迭代5,子迭代6');
$project->type->range('project,project,project,project,sprint{6},stage{6}');
$project->grade->range('1,1,1,1,2{6},3{6}');
$project->parent->range('0,0,0,0,100{6},101{6}');
$project->path->range(',11,,60,,61,,100,,100,101,,100,102,,100,103,,100,104,,100,105,,100,106,,101,201,,102,202,,103,203,,104,204,,105,205,,106,206,');
$project->deleted->range('0');
$project->gen(16);

$executionTest = new executionTest();

$emptyArray = array();
$singleGrade1Execution = (object)array('id' => '11', 'name' => '敏捷项目', 'grade' => 1, 'path' => ',11,');
$singleGrade2Execution = (object)array('id' => '101', 'name' => '迭代1', 'grade' => 2, 'path' => ',100,101,');
$singleGrade3Execution = (object)array('id' => '201', 'name' => '子迭代1', 'grade' => 3, 'path' => ',100,101,201,');

$mixedExecutions = array(
    '11'  => (object)array('id' => '11', 'name' => '敏捷项目', 'grade' => 1, 'path' => ',11,'),
    '101' => (object)array('id' => '101', 'name' => '迭代1', 'grade' => 2, 'path' => ',100,101,'),
    '201' => (object)array('id' => '201', 'name' => '子迭代1', 'grade' => 3, 'path' => ',100,101,201,'),
);

$invalidPathExecution = array(
    '999' => (object)array('id' => '999', 'name' => '无效执行', 'grade' => 2, 'path' => ',888,999,'),
);

$largeExecutionSet = array();
for($i = 101; $i <= 108; $i++)
{
    $largeExecutionSet[$i] = (object)array(
        'id' => $i,
        'name' => '迭代' . ($i - 100),
        'grade' => 2,
        'path' => ',100,' . $i . ','
    );
}

r($executionTest->getFullNameListTest($emptyArray)) && p() && e('0');
r($executionTest->getFullNameListTest(array('11' => $singleGrade1Execution))) && p('11') && e('敏捷项目');
r($executionTest->getFullNameListTest(array('101' => $singleGrade2Execution))) && p('101') && e('迭代项目/迭代1');
r($executionTest->getFullNameListTest($mixedExecutions)) && p('11,101,201') && e('敏捷项目,迭代项目/迭代1,迭代项目/迭代1/子迭代1');
r($executionTest->getFullNameListTest($invalidPathExecution)) && p('999') && e('无效执行');
r(count($executionTest->getFullNameListTest($largeExecutionSet))) && p() && e('8');