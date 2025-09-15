#!/usr/bin/env php
<?php

/**

title=测试 programModel::getChildrenPairsByID();
timeout=0
cid=0

- 获取项目集ID为1的所有子项目集和项目
 - 属性3 @子项目集1
 - 属性4 @子项目集2
- 获取项目集ID为2的所有子项目集和项目属性5 @子项目集3
- 获取不存在的项目集ID的子项目集和项目 @~~
- 获取没有子项目集的项目集ID的子项目集和项目 @~~
- 获取已删除项目集的子项目集和项目（不包含已删除的子项目集） @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/program.unittest.class.php';
su('admin');

$table = zenData('project');
$table->id->range('1-10');
$table->name->range('项目集1,项目集2,子项目集1,子项目集2,子项目集3,项目1,项目2,项目3,已删除项目集,子项目集5');
$table->type->range('program{5},project{3},program{2}');
$table->parent->range('0,0,1,1,2,3,4,0,0,9');
$table->deleted->range('0{8},1{1},0{1}');
$table->status->range('doing{8},closed{1},doing{1}');
$table->grade->range('1,1,2,2,2,3,3,1,1,2');
$table->path->range(',1,,2,,1,3,,1,4,,2,5,,1,3,6,,1,4,7,,8,,9,10,');
$table->gen(10);

$programTest = new programTest();

r($programTest->getChildrenPairsByIDTest(1)) && p('3,4') && e('子项目集1,子项目集2'); // 获取项目集ID为1的所有子项目集和项目
r($programTest->getChildrenPairsByIDTest(2)) && p('5') && e('子项目集3'); // 获取项目集ID为2的所有子项目集和项目
r($programTest->getChildrenPairsByIDTest(999)) && p() && e('~~'); // 获取不存在的项目集ID的子项目集和项目
r($programTest->getChildrenPairsByIDTest(8)) && p() && e('~~'); // 获取没有子项目集的项目集ID的子项目集和项目
r($programTest->getChildrenPairsByIDTest(9)) && p() && e('~~'); // 获取已删除项目集的子项目集和项目（不包含已删除的子项目集）