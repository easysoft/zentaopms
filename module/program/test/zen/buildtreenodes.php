#!/usr/bin/env php
<?php

/**

title=测试 programZen::buildTreeNodes();
timeout=0
cid=17727

- 执行programTest模块的buildTreeNodesTest方法，参数是array  @0
- 执行programTest模块的buildTreeNodesTest方法，参数是$programs, 0
 - 第0条的id属性 @1
 - 第1条的id属性 @2
- 执行programTest模块的buildTreeNodesTest方法，参数是$programs, 0 第0条的text属性 @顶级项目集A
- 执行programTest模块的buildTreeNodesTest方法，参数是$programs, 1
 - 第0条的id属性 @3
 - 第1条的id属性 @4
- 执行programTest模块的buildTreeNodesTest方法，参数是$programs, 3
 - 第0条的id属性 @5
 - 第0条的text属性 @孙项目集E
- 执行programTest模块的buildTreeNodesTest方法，参数是$programs, 0
 - 第0条的id属性 @1
 - 第0条的text属性 @顶级项目集A
 - 第0条的keys属性 @dingjixiangmujia djxmja
- 执行programTest模块的buildTreeNodesTest方法，参数是$orphanPrograms, 0
 - 第0条的id属性 @3
 - 第1条的id属性 @4
- 执行programTest模块的buildTreeNodesTest方法，参数是$orphanPrograms, 0 第0条的items:0:id属性 @~~
- 执行programTest模块的buildTreeNodesTest方法，参数是$orphanPrograms, 0 第1条的text属性 @子项目集D

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/programzen.unittest.class.php';

su('admin');

$programTest = new programTest();

$program1 = new stdClass();
$program1->id = 1;
$program1->name = '顶级项目集A';
$program1->type = 'program';
$program1->parent = 0;

$program2 = new stdClass();
$program2->id = 2;
$program2->name = '顶级项目集B';
$program2->type = 'program';
$program2->parent = 0;

$program3 = new stdClass();
$program3->id = 3;
$program3->name = '子项目集C';
$program3->type = 'program';
$program3->parent = 1;

$program4 = new stdClass();
$program4->id = 4;
$program4->name = '子项目集D';
$program4->type = 'program';
$program4->parent = 1;

$program5 = new stdClass();
$program5->id = 5;
$program5->name = '孙项目集E';
$program5->type = 'program';
$program5->parent = 3;

$project1 = new stdClass();
$project1->id = 8;
$project1->name = '独立项目1';
$project1->type = 'project';
$project1->parent = 0;

$project2 = new stdClass();
$project2->id = 9;
$project2->name = '独立项目2';
$project2->type = 'project';
$project2->parent = 0;

$programs = array(1 => $program1, 2 => $program2, 3 => $program3, 4 => $program4, 5 => $program5, 8 => $project1, 9 => $project2);

$orphanPrograms = array(3 => $program3, 4 => $program4, 5 => $program5);

r($programTest->buildTreeNodesTest(array(), 0)) && p() && e('0'); // 查看空数组输入
r($programTest->buildTreeNodesTest($programs, 0)) && p('0:id;1:id') && e('1;2'); // 查看单层顶级项目集(parent=0)
r($programTest->buildTreeNodesTest($programs, 0)) && p('0:text') && e('顶级项目集A'); // 查看树节点属性完整性
r($programTest->buildTreeNodesTest($programs, 1)) && p('0:id;1:id') && e('3;4'); // 查看多层嵌套项目集树形结构 正确构建父子关系和递归子节点
r($programTest->buildTreeNodesTest($programs, 3)) && p('0:id,text') && e('5,孙项目集E'); // 查看指定父ID构建子树 返回指定父ID的子项目集
r($programTest->buildTreeNodesTest($programs, 0)) && p('0:id,text,keys') && e('1,顶级项目集A,dingjixiangmujia djxmja'); // 查看树节点属性完整性 包含id、text、label、keys等必需属性
r($programTest->buildTreeNodesTest($orphanPrograms, 0)) && p('0:id;1:id') && e('3;4'); // 查看孤立项目集 正确构建父子关系和递归子节点
r($programTest->buildTreeNodesTest($orphanPrograms, 0)) && p('0:items:0:id') && e('~~'); // 查看孤立项目集 正确构建父子关系和递归子节点
r($programTest->buildTreeNodesTest($orphanPrograms, 0)) && p('1:text') && e('子项目集D'); // 查看孤立项目集 正确构建父子关系和递归子节点