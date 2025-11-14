#!/usr/bin/env php
<?php

/**

title=测试 treeZen::printOptionMenuHtml();
timeout=0
cid=19396

- 步骤1：line视图类型测试 @{"name":"line","defaultValue":10,"items":[{"text":"\u4ea7\u54c1\u7ebf1","value":1},{"text":"\u4ea7\u54c1\u7ebf2","value":2}]}

- 步骤2：普通视图类型测试 @{"name":"module","defaultValue":0,"items":[{"text":"\u6a21\u57571","value":1},{"text":"\u6a21\u57572","value":2}]}

- 步骤3：带fieldID的视图类型测试 @{"name":"modules[5]","defaultValue":0,"items":[{"text":"\u6a21\u57571","value":1},{"text":"\u6a21\u57572","value":2}]}

- 步骤4：空optionMenu测试 @{"name":"module","defaultValue":0,"items":[]}

- 步骤5：包含currentModuleID的测试 @{"name":"module","defaultValue":1,"items":[{"text":"\u6a21\u57571","value":1},{"text":"\u6a21\u57572","value":2},{"text":"\u6a21\u57573","value":3}]}

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/treezen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('module');
$table->id->range('1-10');
$table->root->range('1-3');
$table->branch->range('0{5},1{3},2{2}');
$table->name->range('模块1,模块2,模块3,子模块1,子模块2,子模块3,分支模块1,分支模块2,分支模块3,分支模块4');
$table->parent->range('0{3},1{2},2{2},3{3}');
$table->path->range('`,1,`,`,2,`,`,3,`,`,1,4,`,`,1,5,`,`,2,6,`,`,2,7,`,`,3,8,`,`,3,9,`,`,3,10,`');
$table->grade->range('1{3},2{7}');
$table->order->range('1-10');
$table->type->range('story{3},bug{3},case{2},line{2}');
$table->deleted->range('0');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$treeTest = new treeTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($treeTest->printOptionMenuHtmlTest(array('1' => '产品线1', '2' => '产品线2'), 'line', '', 0)) && p() && e('{"name":"line","defaultValue":10,"items":[{"text":"\u4ea7\u54c1\u7ebf1","value":1},{"text":"\u4ea7\u54c1\u7ebf2","value":2}]}'); // 步骤1：line视图类型测试
r($treeTest->printOptionMenuHtmlTest(array('1' => '模块1', '2' => '模块2'), 'story', '', 0)) && p() && e('{"name":"module","defaultValue":0,"items":[{"text":"\u6a21\u57571","value":1},{"text":"\u6a21\u57572","value":2}]}'); // 步骤2：普通视图类型测试
r($treeTest->printOptionMenuHtmlTest(array('1' => '模块1', '2' => '模块2'), 'story', 5, 0)) && p() && e('{"name":"modules[5]","defaultValue":0,"items":[{"text":"\u6a21\u57571","value":1},{"text":"\u6a21\u57572","value":2}]}'); // 步骤3：带fieldID的视图类型测试
r($treeTest->printOptionMenuHtmlTest(array(), 'story', '', 0)) && p() && e('{"name":"module","defaultValue":0,"items":[]}'); // 步骤4：空optionMenu测试
r($treeTest->printOptionMenuHtmlTest(array('1' => '模块1', '2' => '模块2', '3' => '模块3'), 'bug', '', 1)) && p() && e('{"name":"module","defaultValue":1,"items":[{"text":"\u6a21\u57571","value":1},{"text":"\u6a21\u57572","value":2},{"text":"\u6a21\u57573","value":3}]}'); // 步骤5：包含currentModuleID的测试