#!/usr/bin/env php
<?php

/**

title=测试 metricModel::calculateMetricByCode();
timeout=0
cid=17065

- 执行metricTest模块的calculateMetricByCodeTest方法，参数是'user_count'  @0
- 执行metricTest模块的calculateMetricByCodeTest方法，参数是'empty_test'  @0
- 执行metricTest模块的calculateMetricByCodeTest方法，参数是'nonexistent_code'  @0
- 执行metricTest模块的calculateMetricByCodeTest方法，参数是'story_count'  @0
- 执行metricTest模块的calculateMetricByCodeTest方法，参数是'special_chars'  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('metric')->loadYaml('metric_calculatemetricbycode', false, 2)->gen(10);

su('admin');

$metricTest = new metricModelTest();

r($metricTest->calculateMetricByCodeTest('user_count')) && p() && e('0');
r($metricTest->calculateMetricByCodeTest('empty_test')) && p() && e('0');
r($metricTest->calculateMetricByCodeTest('nonexistent_code')) && p() && e('0');
r($metricTest->calculateMetricByCodeTest('story_count')) && p() && e('0');
r($metricTest->calculateMetricByCodeTest('special_chars')) && p() && e('0');