#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

/**

title=测试 screenModel->preparegroupdataset();
timeout=0
cid=1

*/

$screen     = new screenTest();
$component1 = new stdclass();
$component1->type      = 'group';
$component1->isGroup   = false;
$component1->groupList = array();

$component2 = clone $component1;
$component2->styles = 1;

$component3 = clone $component1;
$component3->status = 1;

$attr         = array('w' => 900, 'h' => 300, 'x' => 20, 'y' => 100);
$waterOptions = array(
    'title.show'                                 => false,
    'series.0.outline.show'                      => false,
    'series.0.label.normal.textStyle.fontSize'   => 13,
    'series.0.label.normal.textStyle.fontWeight' => 'normal',
    'series.0.label.normal.textStyle.round'      => 2,
    'series.0.color.0.type'                      => 'linear',
);
