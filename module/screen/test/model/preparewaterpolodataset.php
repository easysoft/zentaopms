#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

/**

title=测试 screenModel->prepareWaterPoloDataset();
timeout=0
cid=18277

- 测试data属性为0的情况下，生成的值是否正确;第option条的dataset属性 @0
- 测试data属性为0.2的情况下，生成的值是否正确;第option条的dataset属性 @0.2
- 测试data属性为0.2的情况下，生成的值是否正确;第option条的dataset属性 @1
- 测试data属性为0.2，styles有值的情况下，是否被修改。
 - 属性type @waterpolo
 - 属性styles @1
- 测试status有值的情况下，是否被修改。属性status @1

*/

$screen     = new screenTest();
$component1 = new stdclass();
$component1->type   = 'waterpolo';
$component1->option = new stdclass();

$component2 = clone $component1;
$component2->styles = 1;

$component3 = clone $component1;
$component3->status = 1;

r($screen->prepareWaterPoloDataset($component1, 0))   && p('option:dataset') && e('0');           // 测试data属性为0的情况下，生成的值是否正确;
r($screen->prepareWaterPoloDataset($component1, 0.2)) && p('option:dataset') && e('0.2');         // 测试data属性为0.2的情况下，生成的值是否正确;
r($screen->prepareWaterPoloDataset($component1, 1))   && p('option:dataset') && e('1');           // 测试data属性为0.2的情况下，生成的值是否正确;
r($screen->prepareWaterPoloDataset($component2, 0.2)) && p('type,styles')    && e('waterpolo,1'); // 测试data属性为0.2，styles有值的情况下，是否被修改。
r($screen->prepareWaterPoloDataset($component3, 0))   && p('status')         && e(1);             // 测试status有值的情况下，是否被修改。
