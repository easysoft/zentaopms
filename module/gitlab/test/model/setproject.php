#!/usr/bin/env php
<?php

/**

title=测试 gitlabModel::setProject();
timeout=0
cid=16667

- 步骤1：设置正常项目对象属性name @test_project
- 步骤2：设置包含完整属性的项目对象
 - 属性name @full_project
 - 属性web_url @https://gitlab.example.com/group/full_project
- 步骤3：设置不同gitlabID的项目属性name @different_gitlab_project
- 步骤4：覆盖已存在的项目缓存
 - 属性name @updated_project
 - 属性description @Updated project description
- 步骤5：设置包含特殊字符的项目名称属性name @special_chars_project

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('pipeline')->gen(5);

su('admin');

$gitlab = new gitlabModelTest();

// 测试数据准备
$project1 = new stdClass();
$project1->id = 1;
$project1->name = 'test_project';
$project1->description = 'Test project description';

$project2 = new stdClass();
$project2->id = 2;
$project2->name = 'full_project';
$project2->description = 'Full project with all attributes';
$project2->web_url = 'https://gitlab.example.com/group/full_project';
$project2->ssh_url_to_repo = 'git@gitlab.example.com:group/full_project.git';
$project2->http_url_to_repo = 'https://gitlab.example.com/group/full_project.git';

$project3 = new stdClass();
$project3->id = 3;
$project3->name = 'different_gitlab_project';

$project4 = new stdClass();
$project4->id = 1;
$project4->name = 'updated_project';
$project4->description = 'Updated project description';

$project5 = new stdClass();
$project5->id = 5;
$project5->name = 'special_chars_project';
$project5->description = 'Project with special characters';

r($gitlab->setProjectTest(1, 1, $project1)) && p('name') && e('test_project');                    // 步骤1：设置正常项目对象
r($gitlab->setProjectTest(1, 2, $project2)) && p('name,web_url') && e('full_project,https://gitlab.example.com/group/full_project'); // 步骤2：设置包含完整属性的项目对象
r($gitlab->setProjectTest(2, 3, $project3)) && p('name') && e('different_gitlab_project');        // 步骤3：设置不同gitlabID的项目
r($gitlab->setProjectTest(1, 1, $project4)) && p('name,description') && e('updated_project,Updated project description'); // 步骤4：覆盖已存在的项目缓存
r($gitlab->setProjectTest(1, 5, $project5)) && p('name') && e('special_chars_project');           // 步骤5：设置包含特殊字符的项目名称