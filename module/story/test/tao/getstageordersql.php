#!/usr/bin/env php
<?php

/**

title=测试 storyTao::getStageOrderSQL();
timeout=0
cid=0

- 步骤1：不传入别名时以 INSTR 函数开头 @1
- 步骤2：不传入别名时以 stage 字段结尾 @1
- 步骤3：阶段顺序与业务流程顺序一致 @1
- 步骤4：传入别名 t1 时使用 t1.stage 字段 @1
- 步骤5：传入别名 t2 时使用 t2.stage 字段 @1
- 步骤6：不传别名与传入空别名结果一致 @1
- 步骤7：按该表达式升序排序时第一行为 wait @wait
- 步骤8：按该表达式升序排序时最后一行为 closed @closed

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$storyTest = new storyTaoTest();

// 4. 测试步骤1：不传入别名时以 INSTR 函数开头
$stageOrderSQL = $storyTest->getStageOrderSQLTest();
r((int)(strpos($stageOrderSQL, "INSTR('") === 0)) && p() && e('1'); // 步骤1：不传入别名时以 INSTR 函数开头

// 5. 测试步骤2：不传入别名时以 stage 字段结尾
r((int)(substr($stageOrderSQL, -10) === ', `stage`)')) && p() && e('1'); // 步骤2：不传入别名时以 stage 字段结尾

// 6. 测试步骤3：阶段顺序与业务流程顺序一致
$expectedStages = 'wait,planned,projected,designing,designed,developing,developed,testing,tested,verified,rejected,delivering,delivered,released,closed';
preg_match("/INSTR\('(.*)', `stage`\)$/", $stageOrderSQL, $matches);
$stageOrderList = isset($matches[1]) ? rtrim($matches[1], ',') : '';
r((int)($stageOrderList === $expectedStages)) && p() && e('1'); // 步骤3：阶段顺序与业务流程顺序一致

// 7. 测试步骤4：传入别名 t1 时使用 t1.stage 字段
r((int)(strpos($storyTest->getStageOrderSQLTest('t1'), ', t1.`stage`)') !== false)) && p() && e('1'); // 步骤4：传入别名 t1 时使用 t1.stage 字段

// 8. 测试步骤5：传入别名 t2 时使用 t2.stage 字段
r((int)(strpos($storyTest->getStageOrderSQLTest('t2'), ', t2.`stage`)') !== false)) && p() && e('1'); // 步骤5：传入别名 t2 时使用 t2.stage 字段

// 9. 测试步骤6：不传别名与传入空别名结果一致
r((int)($storyTest->getStageOrderSQLTest() === $storyTest->getStageOrderSQLTest(''))) && p() && e('1'); // 步骤6：不传别名与传入空别名结果一致

// 10. 测试步骤7：按该表达式升序排序时第一行为 wait
$story = zenData('story');
$story->product->range('1');
$story->type->range('story');
$story->stage->range('closed,released,wait,developing,planned');
$story->gen(5);

$stories = $storyTest->instance->dao->select("id, `stage`, " . $storyTest->getStageOrderSQLTest() . " as `stageOrder`")
    ->from(TABLE_STORY)
    ->orderBy('stageOrder_asc,id_asc')
    ->fetchAll();
r($stories[0]->stage) && p() && e('wait'); // 步骤7：按该表达式升序排序时第一行为 wait

// 11. 测试步骤8：按该表达式升序排序时最后一行为 closed
r($stories[count($stories) - 1]->stage) && p() && e('closed'); // 步骤8：按该表达式升序排序时最后一行为 closed
