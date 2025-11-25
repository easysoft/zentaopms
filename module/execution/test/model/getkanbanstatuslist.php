#!/usr/bin/env php
<?php

/**

title=测试 executionModel::getKanbanStatusList();
timeout=0
cid=16322

- 步骤1：验证状态列表数量 @7
- 步骤2：验证wait状态值属性wait @未开始
- 步骤3：验证doing状态值属性doing @进行中
- 步骤4：验证done状态值属性done @已完成
- 步骤5：验证pause状态值属性pause @已暂停
- 步骤6：验证cancel状态值属性cancel @已取消
- 步骤7：验证closed状态值属性closed @已关闭

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';

su('admin');

$executionTest = new executionTest();

r($executionTest->getKanbanStatusListTest(0)) && p() && e('7');                      // 步骤1：验证状态列表数量
r($executionTest->getKanbanStatusListTest(1)) && p('wait') && e('未开始');           // 步骤2：验证wait状态值
r($executionTest->getKanbanStatusListTest(1)) && p('doing') && e('进行中');          // 步骤3：验证doing状态值
r($executionTest->getKanbanStatusListTest(1)) && p('done') && e('已完成');           // 步骤4：验证done状态值
r($executionTest->getKanbanStatusListTest(1)) && p('pause') && e('已暂停');          // 步骤5：验证pause状态值
r($executionTest->getKanbanStatusListTest(1)) && p('cancel') && e('已取消');         // 步骤6：验证cancel状态值
r($executionTest->getKanbanStatusListTest(1)) && p('closed') && e('已关闭');         // 步骤7：验证closed状态值