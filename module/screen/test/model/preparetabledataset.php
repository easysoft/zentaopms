#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

/**

title=测试 screenModel->prepareTableDataset();
timeout=0
cid=1

*/

$screen     = new screenTest();
$component1 = new stdclass();
$component1->type   = 'table';
$component1->option = $component1->chartConfig = new stdclass();
