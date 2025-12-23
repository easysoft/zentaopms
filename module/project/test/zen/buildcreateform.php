#!/usr/bin/env php
<?php
/**

title=测试 projectZen::buildCreateForm();
timeout=0
cid=0

- 创建敏捷项目
 - 属性title @创建项目
 - 属性model @scrum
 - 属性programID @0
 - 属性copyProjectID @0
- 创建瀑布项目
 - 属性model @waterfall
 - 属性programID @1
 - 属性copyProjectID @0
- 复制IPD项目进行创建
 - 属性model @ipd
 - 属性programID @1
 - 属性copyProjectID @1
- 创建时关联产品1属性productID @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$table = zenData('project');
$table->id->range('1-10');
$table->name->range('项目1,项目2,项目3,项目4,项目5,项目6,项目7,项目8,项目9,项目10');
$table->type->range('project');
$table->status->range('doing');
$table->multiple->range('0,1');
$table->gen(10);

zenData('user')->gen(5);
su('admin');

$projectTest = new projectzenTest();
$result1 = $projectTest->buildCreateFormTest('scrum', 0, 0, '');
r($result1) && p('title,model,programID,copyProjectID') && e('创建项目,scrum,0,0'); // 创建敏捷项目

$result2 = $projectTest->buildCreateFormTest('waterfall', 1, 0, '');
r($result2) && p('model,programID,copyProjectID') && e('waterfall,1,0'); // 创建瀑布项目

$result3 = $projectTest->buildCreateFormTest('ipd', 1, 1, '');
r($result3) && p('model,programID,copyProjectID') && e('ipd,1,1'); // 复制IPD项目进行创建

$result4 = $projectTest->buildCreateFormTest('scrum', 0, 0, 'productID=1,branchID=0');
r($result4) && p('productID') && e('1'); // 创建时关联产品1
