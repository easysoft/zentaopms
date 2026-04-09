#!/usr/bin/env php
<?php

/**

title=测试 programZen::buildTree();
timeout=0
cid=17726

- 查看空数组输入 @0
- 查看单层顶级项目集(parent=0)第0条的id属性 @1
- 查看树节点属性完整性第0条的text属性 @顶级项目集A
- 查看多层嵌套项目集树形结构 正确构建父子关系和递归子节点第0条的id属性 @3
- 查看指定父ID构建子树 返回指定父ID的子项目集
 - 第0条的id属性 @5
 - 第0条的text属性 @孙项目集E
- 查看树节点属性完整性 包含id、text、label、keys等必需属性
 - 第0条的id属性 @1
 - 第0条的text属性 @顶级项目集A
 - 第0条的keys属性 @dingjixiangmujia djxmja

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

r($programTest->buildTreeTest(array(), 0)) && p() && e('0'); // 查看空数组输入
r($programTest->buildTreeTest($programs, 0)) && p('0:id') && e('1'); // 查看单层顶级项目集(parent=0)
r($programTest->buildTreeTest($programs, 0)) && p('0:text') && e('顶级项目集A'); // 查看树节点属性完整性
r($programTest->buildTreeTest($programs, 1)) && p('0:id') && e('3'); // 查看多层嵌套项目集树形结构 正确构建父子关系和递归子节点
r($programTest->buildTreeTest($programs, 3)) && p('0:id,text') && e('5,孙项目集E'); // 查看指定父ID构建子树 返回指定父ID的子项目集
r($programTest->buildTreeTest($programs, 0)) && p('0:id,text,keys') && e('1,顶级项目集A,dingjixiangmujia djxmja'); // 查看树节点属性完整性 包含id、text、label、keys等必需属性