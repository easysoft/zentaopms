#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->createProject();
cid=1

- 测试创建 project1 到项目集 1 @project:3,项目1,2059-12-31,open; program:0000-00-00,0000-00-00; actionCount:2

- 测试创建 project2 到项目集 1 @project:4,项目2,2024-12-31,open; program:0000-00-00,2024-12-31; actionCount:1

- 测试创建 project1 到项目集 2 @project:5,项目1,2059-12-31,open; program:0000-00-00,0000-00-00; actionCount:2

- 测试创建 project2 到项目集 2 @project:6,项目2,2024-12-31,open; program:0000-00-00,2024-12-31; actionCount:1

- 测试创建 项目名称空 的项目到项目集 1第name条的0属性 @『项目名称』不能为空。
- 测试重复创建 project1 到项目集 2第name条的0属性 @『项目名称』已经有『项目1』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';

zdTable('user')->gen(5);
zdTable('action')->gen(0);

$project = zdTable('project');
$project->begin->range('2023-11-01');
$project->end->range('2023-12-31');
$project->gen(2);

su('admin');

$upgrade = new upgradeTest();

/* 没有 end, projectAcl 和 team 的项目，项目状态关闭。 */
$project1 = new stdclass();
$project1->projectName   = '项目1';
$project1->projectStatus = 'closed';
$project1->team          = '';
$project1->begin         = '2023-12-01';
$project1->PM            = 'user1';

/* 有 end, projectAcl 和 team 的项目，且起止时间超出项目集的起止时间。 */
$project2 = new stdclass();
$project2->projectName   = '项目2';
$project2->projectStatus = 'wait';
$project2->team          = '';
$project2->begin         = '2021-12-01';
$project2->end           = '2024-12-31';
$project2->PM            = 'user1';

/* 项目名称为空。 */
$emptyName = new stdclass();
$emptyName->projectName   = '';
$emptyName->projectStatus = 'wait';
$emptyName->team          = '';
$emptyName->begin         = '2023-12-01';
$emptyName->PM            = 'user1';

$programID = array(1, 2);

r($upgrade->createProjectTest($programID[0], $project1))  && p()         && e('project:3,项目1,2059-12-31,open; program:0000-00-00,0000-00-00; actionCount:2');                // 测试创建 project1 到项目集 1
r($upgrade->createProjectTest($programID[0], $project2))  && p()         && e('project:4,项目2,2024-12-31,open; program:0000-00-00,2024-12-31; actionCount:1');                // 测试创建 project2 到项目集 1
r($upgrade->createProjectTest($programID[1], $project1))  && p()         && e('project:5,项目1,2059-12-31,open; program:0000-00-00,0000-00-00; actionCount:2');                // 测试创建 project1 到项目集 2
r($upgrade->createProjectTest($programID[1], $project2))  && p()         && e('project:6,项目2,2024-12-31,open; program:0000-00-00,2024-12-31; actionCount:1');                // 测试创建 project2 到项目集 2
r($upgrade->createProjectTest($programID[0], $emptyName)) && p('name:0') && e('『项目名称』不能为空。');                                                                        // 测试创建 项目名称空 的项目到项目集 1
r($upgrade->createProjectTest($programID[1], $project1))  && p('name:0') && e('『项目名称』已经有『项目1』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。'); // 测试重复创建 project1 到项目集 2
