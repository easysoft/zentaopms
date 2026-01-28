#!/usr/bin/env php
<?php

/**

title=测试 metricModel::getBaseCalcPath();
timeout=0
cid=17077

- 执行metricTest模块的getBaseCalcPathTest方法  @module/metric/calc.class.php
- 执行metricTest模块的getFullBaseCalcPathTest方法，参数是, 'metric') !== false  @1
- 执行metricTest模块的getFullBaseCalcPathTest方法，参数是, -14  @calc.class.php
- 执行metricTest模块的getFullBaseCalcPathTest方法  @string
- 执行metricTest模块的getFullBaseCalcPathTest方法  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$metricTest = new metricModelTest();

r($metricTest->getBaseCalcPathTest()) && p() && e('module/metric/calc.class.php');
r(strpos($metricTest->getFullBaseCalcPathTest(), 'metric') !== false) && p() && e('1');
r(substr($metricTest->getFullBaseCalcPathTest(), -14)) && p() && e('calc.class.php');
r(gettype($metricTest->getFullBaseCalcPathTest())) && p() && e('string');
r(file_exists($metricTest->getFullBaseCalcPathTest())) && p() && e('1');