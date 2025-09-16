#!/usr/bin/env php
<?php

/**

title=测试 programZen::buildTree();
timeout=0
cid=0

- 步骤1：正常构建根节点树形结构
 - 第0条的id属性 @1
 - 第0条的text属性 @项目集1
- 步骤2：构建子节点树形结构
 - 第0条的id属性 @2
 - 第0条的text属性 @子项目集1
- 步骤3：空数组输入处理 @0
- 步骤4：无效父级ID处理 @0
- 步骤5：包含非program类型数据过滤第1条的text属性 @项目集2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/program.unittest.class.php';

zenData('project');

su('admin');

$programTest = new programTest();

// 准备测试数据
$programs = array();

// 创建根级项目集
$program1 = new stdclass();
$program1->id = 1;
$program1->name = '项目集1';
$program1->type = 'program';
$program1->parent = 0;
$program1->grade = 1;
$programs[] = $program1;

// 创建子级项目集
$program2 = new stdclass();
$program2->id = 2;
$program2->name = '子项目集1';
$program2->type = 'program';
$program2->parent = 1;
$program2->grade = 2;
$programs[] = $program2;

// 创建另一个根级项目集
$program3 = new stdclass();
$program3->id = 3;
$program3->name = '项目集2';
$program3->type = 'program';
$program3->parent = 0;
$program3->grade = 1;
$programs[] = $program3;

// 创建非program类型数据
$project1 = new stdclass();
$project1->id = 4;
$project1->name = '项目1';
$project1->type = 'project';
$project1->parent = 1;
$project1->grade = 2;
$programs[] = $project1;

// 创建深层子项目集
$program4 = new stdclass();
$program4->id = 5;
$program4->name = '深层子项目集';
$program4->type = 'program';
$program4->parent = 2;
$program4->grade = 3;
$programs[] = $program4;

r($programTest->buildTreeTest($programs, 0)) && p('0:id,text') && e('1,项目集1'); // 步骤1：正常构建根节点树形结构
r($programTest->buildTreeTest($programs, 1)) && p('0:id,text') && e('2,子项目集1'); // 步骤2：构建子节点树形结构
r($programTest->buildTreeTest(array(), 0)) && p() && e('0'); // 步骤3：空数组输入处理
r($programTest->buildTreeTest($programs, 999)) && p() && e('0'); // 步骤4：无效父级ID处理
r($programTest->buildTreeTest($programs, 0)) && p('1:text') && e('项目集2'); // 步骤5：包含非program类型数据过滤