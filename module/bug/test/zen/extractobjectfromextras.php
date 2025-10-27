#!/usr/bin/env php
<?php

/**

title=测试 bugZen::extractObjectFromExtras();
timeout=0
cid=0

- 步骤1：正常情况从result获取信息属性title @Test Bug From Result
- 步骤2：从现有bug复制信息属性title @Bug 1
- 步骤3：从testtask获取buildID属性buildID @trunk
- 步骤4：从todo获取信息属性title @Todo Task 1
- 步骤5：空数组情况属性title @Test Bug

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$bug = zenData('bug');
$bug->id->range('1-10');
$bug->product->range('1{5},2{5}');
$bug->project->range('1{5},2{5}');
$bug->title->range('Bug 1, Bug 2, Bug 3, Bug 4, Bug 5');
$bug->steps->range('Step 1, Step 2, Step 3, Step 4, Step 5');
$bug->status->range('active{5},resolved{5}');
$bug->openedBy->range('admin,user1,user2');
$bug->assignedTo->range('admin,user1,user2');
$bug->severity->range('1-4');
$bug->pri->range('1-4');
$bug->type->range('codeerror,config,install,security,performance');
$bug->gen(5);

zendata('case')->loadYaml('case_preparecreateextras', false, 2)->gen(5);

$testtask = zenData('testtask');
$testtask->id->range('1-5');
$testtask->build->range('trunk,build1,build2,build3,build4');
$testtask->product->range('1{3},2{2}');
$testtask->gen(5);

$todo = zenData('todo');
$todo->id->range('1-5');
$todo->account->range('admin,user1,user2');
$todo->name->range('Todo Task 1, Todo Task 2, Todo Task 3, Todo Task 4, Todo Task 5');
$todo->desc->range('Todo Description 1, Todo Description 2, Todo Description 3, Todo Description 4, Todo Description 5');
$todo->pri->range('1-4');
$todo->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$bugTest = new bugTest();

// 创建基础bug对象生成函数
function createBaseBug() {
    $bug = new stdclass();
    $bug->id = 0;
    $bug->title = 'Test Bug';
    $bug->steps = 'Initial steps';
    $bug->product = 1;
    $bug->project = 1;
    $bug->module = 0;
    $bug->execution = 0;
    $bug->severity = 3;
    $bug->pri = 3;
    $bug->type = 'codeerror';
    return $bug;
}

// 5. 强制要求：必须包含至少5个测试步骤
r($bugTest->extractObjectFromExtrasTest(createBaseBug(), array('runID' => '1', 'resultID' => '1', 'caseID' => '1'))) && p('title') && e('Test Bug From Result'); // 步骤1：正常情况从result获取信息
r($bugTest->extractObjectFromExtrasTest(createBaseBug(), array('bugID' => '1'))) && p('title') && e('Bug 1'); // 步骤2：从现有bug复制信息
r($bugTest->extractObjectFromExtrasTest(createBaseBug(), array('testtask' => '1'))) && p('buildID') && e('trunk'); // 步骤3：从testtask获取buildID
r($bugTest->extractObjectFromExtrasTest(createBaseBug(), array('todoID' => '1'))) && p('title') && e('Todo Task 1'); // 步骤4：从todo获取信息
r($bugTest->extractObjectFromExtrasTest(createBaseBug(), array())) && p('title') && e('Test Bug'); // 步骤5：空数组情况