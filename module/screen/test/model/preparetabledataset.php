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

$component2 = clone $component1;
$component2->styles = 1;

$component3 = clone $component1;
$component3->status = 1;

$headers = array(array(
    array('field' => 'name',   'name' => 'name',   'label' => 'Name'),
    array('field' => 'age',    'name' => 'age',    'label' => 'Age'),
    array('field' => 'gender', 'name' => 'gender', 'label' => 'Gender'),
));
