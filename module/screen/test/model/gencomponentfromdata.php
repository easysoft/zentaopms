#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 screenModel->genComponentFromData();
timeout=0
cid=18227

- 测试类型是text,标题是Text1生成的组件
 - 属性title @Text1
 - 属性type @text
- 测试类型是text,标题是Text2生成的组件
 - 属性title @Text2
 - 属性type @text
- 测试类型是text,标题是Text3生成的组件
 - 属性title @Text3
 - 属性type @text
- 测试类型是waterpolo,标题是Waterpolo1生成的组件
 - 属性title @Waterpolo1
 - 属性type @waterpolo
- 测试类型是table,标题是Table1生成的组件
 - 属性title @Table1
 - 属性type @table

*/

global $tester;
$tester->loadModel('screen');

$attr         = array('w' => 900, 'h' => 300, 'x' => 20, 'y' => 100);
$textOptions  = array('title.show' => false);
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

r($tester->screen->genComponentFromData('text', 'Text1', '', $attr, $textOptions))             && p('title,type') && e('Text1,text');           //测试类型是text,标题是Text1生成的组件信息,title为Text1,type为text
r($tester->screen->genComponentFromData('text', 'Text2', 'desc', $attr, $textOptions))         && p('title,type') && e('Text2,text');           //测试类型是text,标题是Text2生成的组件信息,title为Text2,type为text
r($tester->screen->genComponentFromData('text', 'Text3', 'desc', $attr, array()))              && p('title,type') && e('Text3,text');           //测试类型是text,标题是Text3生成的组件信息,title为Text3,type为text
r($tester->screen->genComponentFromData('waterpolo', 'Waterpolo1', 0.2, $attr, $waterOptions)) && p('title,type') && e('Waterpolo1,waterpolo'); //测试类型是waterpolo,标题是Waterpolo1,数据是0.2生成的组件信息，title为Waterpolo1,type为waterpolo
r($tester->screen->genComponentFromData('table', 'Table1', $tableData, $attr, $tableOptions))  && p('title,type') && e('Table1,table');         //测试类型是table,标题是Table1生成的组件信息，title为Table1,type为table
