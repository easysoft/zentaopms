#!/usr/bin/env php
<?php

/**

title=测试 docZen::buildOutlineList();
timeout=0
cid=0

- 步骤1:测试空内容数组 @0
- 步骤2:测试单个h1标题
 - 第0条的hint属性 @Title 1
 - 第0条的level属性 @1
- 步骤3:测试h1和h2标题层级
 - 第0条的level属性 @1
 - 第1条的level属性 @2
 - 第1条的parent属性 @0
- 步骤4:测试h2和h3标题层级
 - 第0条的level属性 @2
 - 第1条的level属性 @3
 - 第1条的parent属性 @0
- 步骤5:测试多个同级h1标题
 - 第0条的level属性 @1
 - 第0条的parent属性 @-1
 - 第1条的level属性 @1
 - 第1条的parent属性 @-1
- 步骤6:测试h1-h3多层级标题
 - 第0条的level属性 @1
 - 第1条的level属性 @2
 - 第1条的parent属性 @0
 - 第2条的level属性 @3
 - 第2条的parent属性 @1
- 步骤7:测试标题中包含HTML标签第0条的hint属性 @Bold Title
- 步骤8:测试空标题内容第1条的hint属性 @Valid Title

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$docTest = new docZenTest();

r($docTest->buildOutlineListTest(1, array(), array('h1'))) && p() && e('0'); // 步骤1:测试空内容数组
r($docTest->buildOutlineListTest(1, array(0 => '<h1>Title 1</h1>'), array('h1'))) && p('0:hint;0:level') && e('Title 1;1'); // 步骤2:测试单个h1标题
r($docTest->buildOutlineListTest(1, array(0 => '<h1>Title 1</h1>', 1 => '<h2>Title 2</h2>'), array('h1', 'h2'))) && p('0:level;1:level;1:parent') && e('1;2;0'); // 步骤3:测试h1和h2标题层级
r($docTest->buildOutlineListTest(2, array(0 => '<h2>Title 2</h2>', 1 => '<h3>Title 3</h3>'), array('h2', 'h3'))) && p('0:level;1:level;1:parent') && e('2;3;0'); // 步骤4:测试h2和h3标题层级
r($docTest->buildOutlineListTest(1, array(0 => '<h1>Title 1</h1>', 1 => '<h1>Title 2</h1>'), array('h1'))) && p('0:level;0:parent;1:level;1:parent') && e('1;-1;1;-1'); // 步骤5:测试多个同级h1标题
r($docTest->buildOutlineListTest(1, array(0 => '<h1>Title 1</h1>', 1 => '<h2>Title 2</h2>', 2 => '<h3>Title 3</h3>'), array('h1', 'h2', 'h3'))) && p('0:level;1:level;1:parent;2:level;2:parent') && e('1;2;0;3;1'); // 步骤6:测试h1-h3多层级标题
r($docTest->buildOutlineListTest(1, array(0 => '<h1><strong>Bold</strong> Title</h1>'), array('h1'))) && p('0:hint') && e('Bold Title'); // 步骤7:测试标题中包含HTML标签
r($docTest->buildOutlineListTest(1, array(0 => '<h1></h1>', 1 => '<h1>Valid Title</h1>'), array('h1'))) && p('1:hint') && e('Valid Title'); // 步骤8:测试空标题内容