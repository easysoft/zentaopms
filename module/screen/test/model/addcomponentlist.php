#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

/**

title=测试 screenModel->addComponentList();
timeout=0
cid=1

*/

global $app;
$screen = new screenTest();
$scheme = file_get_contents($app->moduleRoot . 'screen/json/screen.json');
$scheme = json_decode($scheme);

$attr         = array('w' => 900, 'h' => 300, 'x' => 20, 'y' => 100);
$waterOptions = array(
    'title.show'                                 => false,
    'series.0.outline.show'                      => false,
    'series.0.label.normal.textStyle.fontSize'   => 13,
    'series.0.label.normal.textStyle.fontWeight' => 'normal',
    'series.0.label.normal.textStyle.round'      => 2,
    'series.0.color.0.type'                      => 'linear',
);
$headers = array(array(
    array('field' => 'name',   'name' => 'name',   'label' => 'Name'),
    array('field' => 'age',    'name' => 'age',    'label' => 'Age'),
    array('field' => 'gender', 'name' => 'gender', 'label' => 'Gender'),
));
$tableDataSet = array(
    array('tester1',  25, 'male'),
    array('tester2',  30, 'female'),
    array('tester3',  35, 'male'),
    array('tester4',  40, 'female'),
);