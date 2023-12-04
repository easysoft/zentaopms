#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/screen.class.php';

/**
title=测试 screenModel->buildChart();
cid=1
pid=1

当component->key为Select时，不会修改component    >> 1
当component的sourceID不存在时，不会修改component >> 1
当component的sourceID存在时，会修改component     >> 1

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

foreach($screen->componentList as $component)
{
    if(isset($component->key) && $component->key === 'Select') continue;
    $chartID = zget($component->chartConfig, 'sourceID', '');
    if(!$chartID) continue;
    $component3 = $component;
}
$component3_ = clone($component3);
$screen->getLatestChartTest($component3);
r($component3 == $component3_) && p('') && e(1);  //当component的sourceID存在时，有可!能会修改component
