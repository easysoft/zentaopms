#!/usr/bin/env php
<?php
/**
title=getDatasetPath
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';

$metric = new metricTest();

r($metric->getDatasetPathTest()) && p() && e('module/metric/dataset.php');
