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
$tableData    = array($headers, array(), array(), $tableDataSet, array(), array());
$tableOptions = array(
    'colNum'    => 4,
    'rowNum'    => 4,
    'headerBGC' => '#fcfdfe',
    'bodyBGC'   => '#fff',
    'borderBGC' => '#e6ecf8',
    'fontColor' => '#000',
    'rowHeight' => 36
);

$components = array();
$components[] = $screen->genComponentFromData('text', 'Title1', 'Title1', $attr);
$components[] = $screen->genComponentFromData('text', '', '', $attr);
$components[] = $screen->genComponentFromData('waterpolo', 'Waterpolo1', 0.2, $attr, $waterOptions);
$components[] = $screen->genComponentFromData('table', 'Table1', $tableData, $attr, $tableOptions);
r($screen->addComponentList($scheme, array())) && p('editCanvasConfig:blendMode') && e('normal');   // 测试componentList属性为空的情况下，生成的默认值是否正确;
r($screen->addComponentList($scheme, $components)->componentList) && p('0:type') && e('text');      // 测试传入componentList属性的情况下，生成的值是否正确;
r($screen->addComponentList($scheme, $components)->componentList) && p('1:type') && e('text');      // 测试传入componentList属性的情况下，生成的值是否正确;
r($screen->addComponentList($scheme, $components)->componentList) && p('2:type') && e('waterpolo'); // 测试传入componentList属性的情况下，生成的值是否正确;
r($screen->addComponentList($scheme, $components)->componentList) && p('3:type') && e('table');     // 测试传入componentList属性的情况下，生成的值是否正确;
