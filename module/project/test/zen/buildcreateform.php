#!/usr/bin/env php
<?php
/**

title=测试 projectZen::buildCreateForm();
timeout=0
cid=0

- 复制项目1创建
 - 属性name @项目1
 - 属性type @project
 - 属性status @doing
- 复制项目2创建
 - 属性name @项目2
 - 属性type @project
 - 属性status @doing
- 复制项目3创建
 - 属性name @项目3
 - 属性type @project
 - 属性status @doing
- 复制项目4创建
 - 属性name @项目4
 - 属性type @project
 - 属性status @doing
- 复制项目5创建
 - 属性name @项目5
 - 属性type @project
 - 属性status @doing

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/projectzen.unittest.class.php';

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
r($projectTest->buildCreateFormTest(1)) && p('name,type,status') && e('项目1,project,doing'); // 复制项目1创建
r($projectTest->buildCreateFormTest(2)) && p('name,type,status') && e('项目2,project,doing'); // 复制项目2创建
r($projectTest->buildCreateFormTest(3)) && p('name,type,status') && e('项目3,project,doing'); // 复制项目3创建
r($projectTest->buildCreateFormTest(4)) && p('name,type,status') && e('项目4,project,doing'); // 复制项目4创建
r($projectTest->buildCreateFormTest(5)) && p('name,type,status') && e('项目5,project,doing'); // 复制项目5创建
