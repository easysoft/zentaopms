#!/usr/bin/env php
<?php

/**

title=测试 metricModel::getLogFile();
timeout=0
cid=17106

- 执行metricTest模块的getLogFileTest方法，参数是, 'metriclib') !== false  @1
- 执行metricTest模块的getLogFileTest方法，参数是, -8) === '.log.php  @1
- 执行metricTest模块的getLogFileTest方法，参数是, 'tmp/log') !== false  @1
- 执行metricTest模块的getLogFileTest方法，参数是, "metriclib.$currentDate.log.php") !== false  @1
- 执行metricTest模块的getLogFileTest方法，参数是 > 0  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$metricTest = new metricModelTest();
$currentDate = date('Ymd');

r(strpos($metricTest->getLogFileTest(), 'metriclib') !== false) && p() && e('1');
r(substr($metricTest->getLogFileTest(), -8) === '.log.php') && p() && e('1');
r(strpos($metricTest->getLogFileTest(), 'tmp/log') !== false) && p() && e('1');
r(strpos($metricTest->getLogFileTest(), "metriclib.$currentDate.log.php") !== false) && p() && e('1');
r(strlen($metricTest->getLogFileTest()) > 0) && p() && e('1');