#!/usr/bin/env php
<?php

/**

title=测试 todoZen::afterBatchEdit();
timeout=0
cid=19285

- 执行todoTest模块的afterBatchEditTest方法，参数是array
 - 属性totalChanges @0
 - 属性processedTodos @0
- 执行todoTest模块的afterBatchEditTest方法，参数是$singleChange
 - 属性totalChanges @1
 - 属性processedTodos @1
 - 属性actionsCreated @1
- 执行todoTest模块的afterBatchEditTest方法，参数是$multipleChanges
 - 属性totalChanges @3
 - 属性processedTodos @3
 - 属性actionsCreated @3
- 执行todoTest模块的afterBatchEditTest方法，参数是$mixedChanges
 - 属性totalChanges @5
 - 属性processedTodos @3
 - 属性actionsCreated @3
- 执行todoTest模块的afterBatchEditTest方法，参数是$largeChanges
 - 属性totalChanges @10
 - 属性processedTodos @10
 - 属性success @1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todozen.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$todoTest = new todoTest();

// 4. 测试步骤

// 测试步骤1：空变更数组处理
r($todoTest->afterBatchEditTest(array())) && p('totalChanges,processedTodos') && e('0,0');

// 测试步骤2：单个待办变更处理
$singleChange = array(
    1 => array('name' => array('old' => '旧名称', 'new' => '新名称'))
);
r($todoTest->afterBatchEditTest($singleChange)) && p('totalChanges,processedTodos,actionsCreated') && e('1,1,1');

// 测试步骤3：多个待办变更处理
$multipleChanges = array(
    1 => array('name' => array('old' => '旧名称1', 'new' => '新名称1')),
    2 => array('status' => array('old' => 'wait', 'new' => 'done')),
    3 => array('pri' => array('old' => '2', 'new' => '1'))
);
r($todoTest->afterBatchEditTest($multipleChanges)) && p('totalChanges,processedTodos,actionsCreated') && e('3,3,3');

// 测试步骤4：包含空变更的混合处理
$mixedChanges = array(
    1 => array('name' => array('old' => '旧名称', 'new' => '新名称')),
    2 => array(), // 空变更
    3 => array('status' => array('old' => 'wait', 'new' => 'done')),
    4 => array(), // 空变更
    5 => array('pri' => array('old' => '3', 'new' => '1'))
);
r($todoTest->afterBatchEditTest($mixedChanges)) && p('totalChanges,processedTodos,actionsCreated') && e('5,3,3');

// 测试步骤5：大量待办变更处理
$largeChanges = array();
for($i = 1; $i <= 10; $i++) {
    $largeChanges[$i] = array('name' => array('old' => "待办{$i}", 'new' => "更新待办{$i}"));
}
r($todoTest->afterBatchEditTest($largeChanges)) && p('totalChanges,processedTodos,success') && e('10,10,1');