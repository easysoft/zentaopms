#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

/**

title=测试 screenModel->prepareTableDataset();
timeout=0
cid=18275

- 测试属性为空的情况下，生成的默认值是否正确;属性type @table
- 测试添加headers属性的情况下，生成的值是否正确;属性type @table
- 测试添加headers和align属性的情况下，生成的值是否正确;属性type @table
- 测试styles有值的情况下，是否被修改。属性styles @1
- 测试status有值的情况下，是否被修改。属性status @1

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

$align   = array('left', 'center', 'right');
$dataset = array(
    array('tester1',  25, 'male'),
    array('tester2',  30, 'female'),
    array('tester3',  35, 'male'),
    array('tester4',  40, 'female'),
);

r($screen->prepareTableDataset($component1, array(), array(), array(), array(), array(), array()))  && p('type')   && e('table'); // 测试属性为空的情况下，生成的默认值是否正确;
r($screen->prepareTableDataset($component1, $headers, array(), array(), array(), array(), array())) && p('type')   && e('table'); // 测试添加headers属性的情况下，生成的值是否正确;
r($screen->prepareTableDataset($component1, $headers, $align, array(), array(), $dataset, array())) && p('type')   && e('table'); // 测试添加headers和align属性的情况下，生成的值是否正确;
r($screen->prepareTableDataset($component2, $headers, $align, array(), array(), $dataset, array())) && p('styles') && e(1);       // 测试styles有值的情况下，是否被修改。
r($screen->prepareTableDataset($component3, $headers, $align, array(), array(), $dataset, array())) && p('status') && e(1);       // 测试status有值的情况下，是否被修改。
