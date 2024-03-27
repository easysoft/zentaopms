#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/screen.class.php';

/**

title=测试 screenModel->buildChart();
timeout=0
cid=1

- 当component->key为Select时，不会修改component @1
- 当component的sourceID不存在时，不会修改component @1

*/

$screen = new screenTest();

$components = $screen->getAllComponent(array('key' => 'Select'));

$component1  = current($components);
$component1_ = clone($component1);
$screen->getLatestChartTest($component1);
r($component1 == $component1_) && p('') && e(1);  //当component->key为Select时，不会修改component

foreach($components as $component)
{
    $chartID = zget($component->chartConfig, 'sourceID', '');

    if(!$chartID)
    {
        $component2 = $component;
        break;
    }
}
$component2_ = clone($component2);
$screen->getLatestChartTest($component2);
r($component2 && $component2 == $component2_) && p('') && e(1);  //当component的sourceID不存在时，不会修改component