#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$execution = new executionModelTest();

/**

title=- 测试步骤1：获取默认看板颜色列表属性wait @
timeout=0
cid=16318

- 测试步骤1：获取默认看板颜色列表属性wait @#7EC5FF
- 测试步骤2：验证wait状态的颜色值 @#7EC5FF
- 测试步骤3：统计颜色列表数量 @6
- 测试步骤4：测试空颜色列表处理 @0
- 测试步骤5：测试自定义颜色列表属性wait @#FF0000
- 测试步骤6：验证所有预期状态键存在 @1

*/

r($execution->getKanbanColorListTest('default')) && p('wait') && e('#7EC5FF'); // 测试步骤1：获取默认看板颜色列表
r($execution->getKanbanColorListTest('specific_color')) && p() && e('#7EC5FF'); // 测试步骤2：验证wait状态的颜色值
r($execution->getKanbanColorListTest('count')) && p() && e('6'); // 测试步骤3：统计颜色列表数量
r($execution->getKanbanColorListTest('empty')) && p() && e('0'); // 测试步骤4：测试空颜色列表处理
r($execution->getKanbanColorListTest('custom')) && p('wait') && e('#FF0000'); // 测试步骤5：测试自定义颜色列表
r($execution->getKanbanColorListTest('all_keys')) && p() && e('1'); // 测试步骤6：验证所有预期状态键存在