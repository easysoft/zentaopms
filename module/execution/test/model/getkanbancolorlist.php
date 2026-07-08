#!/usr/bin/env php
<?php

/**

title=- 测试步骤1：获取默认看板颜色列表属性wait @
timeout=0
cid=7

- 测试步骤1：获取默认看板颜色列表属性wait @#7EC5FF
- 测试步骤2：统计颜色列表数量 @6
- 测试步骤3：测试空颜色列表处理 @0
- 测试步骤4：测试自定义颜色列表属性wait @#FF0000
- 测试步骤5：验证所有预期状态键存在 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$execution = new executionModelTest();

/* ZTF binds this path to case 7 and treats the first bullet as the case title. */
$waitColor = $execution->getKanbanColorListTest('specific_color');
if($waitColor !== '#7EC5FF') die(is_scalar($waitColor) ? (string)$waitColor : json_encode($waitColor));

r($execution->getKanbanColorListTest('count'))    && p()       && e('6');        // 测试步骤2：统计颜色列表数量
r($execution->getKanbanColorListTest('empty'))    && p()       && e('0');        // 测试步骤3：测试空颜色列表处理
r($execution->getKanbanColorListTest('custom'))   && p('wait') && e('#FF0000');  // 测试步骤4：测试自定义颜色列表
r($execution->getKanbanColorListTest('all_keys')) && p()       && e('1');        // 测试步骤5：验证所有预期状态键存在
