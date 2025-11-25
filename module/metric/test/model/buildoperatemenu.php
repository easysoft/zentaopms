#!/usr/bin/env php
<?php

/**

title=测试 metricModel::buildOperateMenu();
timeout=0
cid=17062

- 测试步骤1：内置metric生成空main菜单 @0
- 测试步骤2：wait阶段metric生成edit菜单第edit条的icon属性 @edit
- 测试步骤3：released阶段metric生成delist菜单第delist条的icon属性 @ban-circle
- 测试步骤4：自定义metric生成delete菜单第delete条的icon属性 @trash
- 测试步骤5：有日期类型metric生成recalculate菜单第recalculate条的icon属性 @refresh

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/calc.unittest.class.php';

// 准备测试数据
zenData('metric')->gen(0);

su('admin');
$metricTest = new metricTest();

// 获取一个测试用的metric对象作为基础
$metricByCode = $metricTest->getByCode('count_of_program');
$baseMetric = $metricTest->getByID($metricByCode->id);

// 测试用例1：内置metric (builtin='1')
$builtinMetric = json_decode(json_encode($baseMetric));
$builtinMetric->builtin = '1';

// 测试用例2：wait阶段自定义metric
$waitMetric = json_decode(json_encode($baseMetric));
$waitMetric->builtin = '0';
$waitMetric->stage = 'wait';

// 测试用例3：released阶段自定义metric
$releasedMetric = json_decode(json_encode($baseMetric));
$releasedMetric->builtin = '0';
$releasedMetric->stage = 'released';

// 测试用例4：可删除的自定义metric
$deletableMetric = json_decode(json_encode($baseMetric));
$deletableMetric->builtin = '0';
$deletableMetric->stage = 'wait';

// 测试用例5：带日期类型的released metric（可重新计算）
$recalculateMetric = json_decode(json_encode($baseMetric));
$recalculateMetric->builtin = '0';
$recalculateMetric->stage = 'released';
$recalculateMetric->dateType = 'day';

// 执行5个测试步骤
r($metricTest->buildOperateMenu($builtinMetric, 'main')) && p() && e('0');                             // 测试步骤1：内置metric生成空main菜单
r($metricTest->buildOperateMenu($waitMetric, 'suffix')) && p('edit:icon') && e('edit');                  // 测试步骤2：wait阶段metric生成edit菜单
r($metricTest->buildOperateMenu($releasedMetric, 'main')) && p('delist:icon') && e('ban-circle');        // 测试步骤3：released阶段metric生成delist菜单
r($metricTest->buildOperateMenu($deletableMetric, 'suffix')) && p('delete:icon') && e('trash');          // 测试步骤4：自定义metric生成delete菜单
r($metricTest->buildOperateMenu($recalculateMetric, 'main')) && p('recalculate:icon') && e('refresh');   // 测试步骤5：有日期类型metric生成recalculate菜单