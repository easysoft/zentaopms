#!/usr/bin/env php
<?php

/**

title=测试 actionModel::getLikeObject();
timeout=0
cid=14902

- 执行action模块的getLikeObjectTest方法，参数是TABLE_PROJECT, 'name', 'name', '%项目集%'  @5
- 执行action模块的getLikeObjectTest方法，参数是TABLE_PROJECT, 'code', 'code', '%program%'  @5
- 执行action模块的getLikeObjectTest方法，参数是TABLE_PROJECT, 'name', 'name', '%不存在%'  @0
- 执行action模块的getLikeObjectTest方法，参数是TABLE_BUG, 'title', 'title', '%测试%'  @3
- 执行action模块的getLikeObjectTest方法，参数是TABLE_PROJECT, 'name', 'name', '%\'OR\'1\'=\'1%'  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$project = zenData('project');
$project->id->range('1-5');
$project->name->range('项目集1,项目集2,项目集3,项目集4,项目集5');
$project->code->range('program1,program2,program3,program4,program5');
$project->type->range('program{5}');
$project->gen(5);

$bug = zenData('bug');
$bug->id->range('1-3');
$bug->title->range('测试Bug1,测试Bug2,测试Bug3');
$bug->gen(3);

zenData('actionrecent')->gen(0);

su('admin');

$action = new actionModelTest();

r($action->getLikeObjectTest(TABLE_PROJECT, 'name', 'name', '%项目集%')) && p() && e('5');
r($action->getLikeObjectTest(TABLE_PROJECT, 'code', 'code', '%program%')) && p() && e('5');
r($action->getLikeObjectTest(TABLE_PROJECT, 'name', 'name', '%不存在%')) && p() && e('0');
r($action->getLikeObjectTest(TABLE_BUG, 'title', 'title', '%测试%')) && p() && e('3');
r($action->getLikeObjectTest(TABLE_PROJECT, 'name', 'name', '%\'OR\'1\'=\'1%')) && p() && e('0');