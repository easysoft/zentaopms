#!/usr/bin/env php
<?php

/**

title=测试 metricModel::getDataset();
timeout=0
cid=17087

- 执行metricTest模块的getDatasetTest方法 属性className @dataset
- 执行metricTest模块的getDatasetTest方法 属性dao @object
- 执行metricTest模块的getDatasetTest方法 属性config @object
- 执行metricTest模块的getDatasetTest方法 属性vision @string
- 执行metricTest模块的getDatasetTest方法，参数是null 属性className @dataset

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metric.unittest.class.php';

su('admin');

$metricTest = new metricTest();

r($metricTest->getDatasetTest()) && p('className') && e('dataset');
r($metricTest->getDatasetTest()) && p('dao') && e('object');
r($metricTest->getDatasetTest()) && p('config') && e('object'); 
r($metricTest->getDatasetTest()) && p('vision') && e('string');
r($metricTest->getDatasetTest(null)) && p('className') && e('dataset');