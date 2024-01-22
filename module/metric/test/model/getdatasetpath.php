#!/usr/bin/env php
<?php

/**

title=getDatasetPath
timeout=0
cid=1

- 执行metric模块的getDatasetPathTest方法  @module/metric/dataset.php

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';

$metric = new metricTest();

r($metric->getDatasetPathTest()) && p() && e('module/metric/dataset.php');