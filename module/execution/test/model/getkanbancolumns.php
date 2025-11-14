#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';

su('admin');

$execution = new executionTest();

/**

title=测试 executionModel::getKanbanColumns();
timeout=0
cid=16319

- 测试步骤1：默认看板列配置 @wait
- 测试步骤2：完整看板列配置属性4 @cancel
- 测试步骤3：验证默认看板列数量 @4
- 测试步骤4：验证完整看板列数量 @6
- 测试步骤5：空对象allCols属性处理 @wait

*/

r($execution->getKanbanColumnsTest('default')) && p('0') && e('wait'); // 测试步骤1：默认看板列配置
r($execution->getKanbanColumnsTest('all_cols')) && p('4') && e('cancel'); // 测试步骤2：完整看板列配置
r($execution->getKanbanColumnsTest('count_default')) && p() && e('4'); // 测试步骤3：验证默认看板列数量
r($execution->getKanbanColumnsTest('count_all')) && p() && e('6'); // 测试步骤4：验证完整看板列数量
r($execution->getKanbanColumnsTest('empty')) && p('0') && e('wait'); // 测试步骤5：空对象allCols属性处理