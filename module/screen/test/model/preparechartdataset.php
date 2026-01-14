#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 screenModel->prepareChartDataset();
timeout=0
cid=18270

- 测试dimensions和sourceData属性为空的情况下，生成的默认值是否正确;属性type @pie
- 测试传入dimensions属性的情况下，生成的值是否正确;属性type @pie
- 测试传入sourceData属性的情况下，生成的值是否正确;属性type @pie
- 测试传入dimensions和sourceData属性的情况下，生成的值是否正确;属性type @pie
- 测试styles有值的情况下，是否被修改。属性styles @1
- 测试status有值的情况下，是否被修改。属性status @1

*/

$screen     = new screenModelTest();
$component1 = new stdclass();
$component1->type   = 'pie';
$component1->option = new stdclass();
$component1->option->dataset = new stdclass();

$component2 = clone $component1;
$component2->styles = 1;

$component3 = clone $component1;
$component3->status = 1;

r($screen->prepareChartDataset($component1, array(), array()))               && p('type')   && e('pie'); // 测试dimensions和sourceData属性为空的情况下，生成的默认值是否正确;
r($screen->prepareChartDataset($component1, array(0,1,2,3,4,5), array()))    && p('type')   && e('pie'); // 测试传入dimensions属性的情况下，生成的值是否正确;
r($screen->prepareChartDataset($component1, array(), array(0,1,2,3,4,5)))    && p('type')   && e('pie'); // 测试传入sourceData属性的情况下，生成的值是否正确;
r($screen->prepareChartDataset($component1, array(0,1), array(0,1,2,3,4,5))) && p('type')   && e('pie'); // 测试传入dimensions和sourceData属性的情况下，生成的值是否正确;
r($screen->prepareChartDataset($component2, array(), array()))               && p('styles') && e(1);     // 测试styles有值的情况下，是否被修改。
r($screen->prepareChartDataset($component3, array(), array()))               && p('status') && e(1);     // 测试status有值的情况下，是否被修改。
