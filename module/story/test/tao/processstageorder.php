#!/usr/bin/env php
<?php

/**

title=测试 storyTao::processStageOrder();
timeout=0
cid=0

- 步骤1：排序中不含阶段字段时原样返回 @id_desc
- 步骤2：阶段升序替换为 stageOrder 升序 @stageOrder_asc
- 步骤3：阶段降序替换为 stageOrder 降序 @stageOrder_desc
- 步骤4：多字段排序时只替换阶段字段 @stageOrder_asc,id_desc
- 步骤5：保留阶段字段之前的前置排序字段 @type,grade,stageOrder_asc
- 步骤6：传入别名时替换带别名的阶段字段 @stageOrder_asc
- 步骤7：别名不匹配时不替换阶段字段 @t1.stage_asc
- 步骤8：不传别名时不替换带表别名的阶段字段 @t2.stage_asc
- 步骤9：已经是 stageOrder 时不会重复替换 @stageOrder_asc
- 步骤10：反引号包裹的阶段字段也能替换 @stageOrder_desc

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$storyTest = new storyTaoTest();

// 4. 测试步骤1：排序中不含阶段字段时原样返回
r($storyTest->processStageOrderTest('id_desc')) && p() && e('id_desc'); // 步骤1：排序中不含阶段字段时原样返回

// 5. 测试步骤2：阶段升序替换为 stageOrder 升序
r($storyTest->processStageOrderTest('stage_asc')) && p() && e('stageOrder_asc'); // 步骤2：阶段升序替换为 stageOrder 升序

// 6. 测试步骤3：阶段降序替换为 stageOrder 降序
r($storyTest->processStageOrderTest('stage_desc')) && p() && e('stageOrder_desc'); // 步骤3：阶段降序替换为 stageOrder 降序

// 7. 测试步骤4：多字段排序时只替换阶段字段
r($storyTest->processStageOrderTest('stage_asc,id_desc')) && p() && e('stageOrder_asc,id_desc'); // 步骤4：多字段排序时只替换阶段字段

// 8. 测试步骤5：保留阶段字段之前的前置排序字段
r($storyTest->processStageOrderTest('type,grade,stage_asc')) && p() && e('type,grade,stageOrder_asc'); // 步骤5：保留阶段字段之前的前置排序字段

// 9. 测试步骤6：传入别名时替换带别名的阶段字段
r($storyTest->processStageOrderTest('t2.stage_asc', 't2')) && p() && e('stageOrder_asc'); // 步骤6：传入别名时替换带别名的阶段字段

// 10. 测试步骤7：别名不匹配时不替换阶段字段
r($storyTest->processStageOrderTest('t1.stage_asc', 't2')) && p() && e('t1.stage_asc'); // 步骤7：别名不匹配时不替换阶段字段

// 11. 测试步骤8：不传别名时不替换带表别名的阶段字段
r($storyTest->processStageOrderTest('t2.stage_asc')) && p() && e('t2.stage_asc'); // 步骤8：不传别名时不替换带表别名的阶段字段

// 12. 测试步骤9：已经是 stageOrder 时不会重复替换
r($storyTest->processStageOrderTest('stageOrder_asc')) && p() && e('stageOrder_asc'); // 步骤9：已经是 stageOrder 时不会重复替换

// 13. 测试步骤10：反引号包裹的阶段字段也能替换
r($storyTest->processStageOrderTest('`stage`_desc')) && p() && e('stageOrder_desc'); // 步骤10：反引号包裹的阶段字段也能替换
