#!/usr/bin/env php
<?php

/**

title=测试 projectZen::buildActivateForm();
timeout=0
cid=17924

- 步骤1：正常项目激活
 - 属性title @激活项目
 - 属性project @1
- 步骤2：已挂起项目激活
 - 属性title @激活项目
 - 属性newBegin @2025-11-11
- 步骤3：已关闭项目激活
 - 属性title @激活项目
 - 属性actions @1
- 步骤4：长期项目激活
 - 属性title @激活项目
 - 属性newEnd @2031-08-13
- 步骤5：验证新开始日期为当前日期属性newBegin @2025-11-11

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/projectzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('project');
$table->id->range('1-10');
$table->name->range('项目1,项目2,项目3,项目4,项目5,项目6,项目7,项目8,项目9,项目10');
$table->type->range('project');
$table->status->range('closed,suspended,closed,suspended,closed,closed,suspended,closed,suspended,closed');
$table->begin->range('`2024-01-01`,`2024-02-01`,`2024-03-01`,`2024-04-01`,`2024-05-01`,`2024-06-01`,`2024-07-01`,`2024-08-01`,`2024-09-01`,`2024-10-01`');
$table->end->range('`2024-06-01`,`2024-07-01`,`2024-08-01`,`2030-01-01`,`2024-10-01`,`2024-11-01`,`2024-12-01`,`2025-01-01`,`2025-02-01`,`2025-03-01`');
$table->gen(10);

$userTable = zenData('user');
$userTable->id->range('1-5');
$userTable->account->range('admin,user1,user2,user3,user4');
$userTable->realname->range('管理员,用户1,用户2,用户3,用户4');
$userTable->gen(5);

$actionTable = zenData('action');
$actionTable->id->range('1-3');
$actionTable->objectType->range('project');
$actionTable->objectID->range('1-3');
$actionTable->action->range('opened,started,suspended');
$actionTable->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$projectTest = new projectzenTest();

// 获取项目对象
$project1 = $projectTest->objectModel->getByID(1);
$project2 = $projectTest->objectModel->getByID(2);
$project3 = $projectTest->objectModel->getByID(3);
$project4 = $projectTest->objectModel->getByID(4);
$project5 = $projectTest->objectModel->getByID(5);

// 5. 强制要求：必须包含至少5个测试步骤
r($projectTest->buildActivateFormTest($project1)) && p('title,project') && e('激活项目,1'); // 步骤1：正常项目激活
r($projectTest->buildActivateFormTest($project2)) && p('title,newBegin') && e('激活项目,2025-11-11'); // 步骤2：已挂起项目激活
r($projectTest->buildActivateFormTest($project3)) && p('title,actions') && e('激活项目,1'); // 步骤3：已关闭项目激活
r($projectTest->buildActivateFormTest($project4)) && p('title,newEnd') && e('激活项目,2031-08-13'); // 步骤4：长期项目激活
r($projectTest->buildActivateFormTest($project5)) && p('newBegin') && e('2025-11-11'); // 步骤5：验证新开始日期为当前日期