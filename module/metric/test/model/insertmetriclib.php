#!/usr/bin/env php
<?php

/**

title=initActionBtn
timeout=0
cid=1

- 测试正常插入 @3
- 测试空数组 @6
- 测试null @10

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';
su('admin');
zdTable('metriclib')->config('metriclib_system_product', true)->gen(0);

$metric = new metricTest();

$records1 = array
(
    array('metricID' => 107, 'metricCode' => 'count_of_bug',         'value' => 10, 'date' => '2020-01-01'),
    array('metricID' => 108, 'metricCode' => 'count_of_actived_bug', 'value' => 5,  'date' => '2020-01-01'),
    array('metricID' => 110, 'metricCode' => 'count_of_closed_bug',  'value' => 3,  'date' => '2020-01-01')
);

$records2 = array
(
    array('metricID' => 107, 'metricCode' => 'count_of_bug',         'value' => 10, 'date' => '2020-01-01'),
    array('metricID' => 108, 'metricCode' => 'count_of_actived_bug', 'value' => 5,  'date' => '2020-01-01'),
    array('metricID' => 110, 'metricCode' => 'count_of_closed_bug',  'value' => 3,  'date' => '2020-01-01'),
    array()
);

$records3 = array
(
    array('metricID' => 107, 'metricCode' => 'count_of_bug',         'value' => 10, 'date' => '2020-01-01'),
    array('metricID' => 108, 'metricCode' => 'count_of_actived_bug', 'value' => 5,  'date' => '2020-01-01'),
    array('metricID' => 110, 'metricCode' => 'count_of_closed_bug',  'value' => 3,  'date' => '2020-01-01'),
    array('metricID' => 110, 'metricCode' => 'count_of_closed_bug',  'value' => 6,  'date' => '2020-01-02'),
    null
);

r($metric->insertMetricLib($records1)) && p('') && e('3'); // 测试正常插入
r($metric->insertMetricLib($records2)) && p('') && e('6'); // 测试空数组
r($metric->insertMetricLib($records3)) && p('') && e('10'); // 测试null