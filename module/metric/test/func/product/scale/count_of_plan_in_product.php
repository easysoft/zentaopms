#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/metric.class.php';

$metric = new metricTest();

$rows = $metric->getData('product');
$calc = $metric->calcMetric('product', 'scale', 'count_of_plan_in_product', $rows);

print_r($calc->getResult());


/**

title=bugModel->close();
cid=1
pid=1

*/
