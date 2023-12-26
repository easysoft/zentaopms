#!/usr/bin/env php
<?php

/**

title=getBaseCalcPath
timeout=0
cid=1

- 执行metric模块的getBaseCalcPathTest方法  @module/metric/calc.class.php

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';

$metric = new metricTest();

r($metric->getBaseCalcPathTest()) && p() && e('module/metric/calc.class.php');