#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

/**

title=测试 screenModel->prepareBoxDataset();
timeout=0
cid=1

*/

$screen     = new screenTest();
$component1 = new stdclass();
$component1->type   = 'box';
$component1->option = new stdclass();
