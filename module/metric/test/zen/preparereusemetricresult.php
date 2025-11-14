#!/usr/bin/env php
<?php

/**

title=测试 metricZen::prepareReuseMetricResult();
timeout=0
cid=17201

- 执行metricZenTest模块的prepareReuseMetricResultZenTest方法，参数是new mockCalc  @0
- 执行metricZenTest模块的prepareReuseMetricResultZenTest方法，参数是new mockCalc  @0
- 执行metricZenTest模块的prepareReuseMetricResultZenTest方法，参数是new mockCalc  @0
- 执行metricZenTest模块的prepareReuseMetricResultZenTest方法，参数是new mockCalc  @0
- 执行metricZenTest模块的prepareReuseMetricResultZenTest方法，参数是new mockCalc  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metriczen.unittest.class.php';

zenData('metric')->loadYaml('metric', false, 2)->gen(10);
zenData('metriclib')->loadYaml('metriclib_system_product', false, 2)->gen(10);

su('admin');

$metricZenTest = new metricZenTest();

// 创建测试用的calc对象
class mockCalc
{
    public $reuseMetrics = array();

    public function __construct($reuseMetrics = array())
    {
        $this->reuseMetrics = $reuseMetrics;
    }

    public function calculate($data)
    {
        return true;
    }
}

// 创建测试用的options数组
$options = array('year' => date('Y'), 'month' => date('n'), 'week' => substr(date('oW'), -2), 'day' => date('j') . ',' . date('j', strtotime('-1 day')));

r($metricZenTest->prepareReuseMetricResultZenTest(new mockCalc(array('test_metric_1', 'test_metric_2')), $options)) && p() && e('0');
r($metricZenTest->prepareReuseMetricResultZenTest(new mockCalc(array()), $options)) && p() && e('0');
r($metricZenTest->prepareReuseMetricResultZenTest(new mockCalc(array('metric1', 'metric2', 'metric3')), $options)) && p() && e('0');
r($metricZenTest->prepareReuseMetricResultZenTest(new mockCalc(array('invalid_metric')), $options)) && p() && e('0');
r($metricZenTest->prepareReuseMetricResultZenTest(new mockCalc(array('test!@#_metric')), $options)) && p() && e('0');