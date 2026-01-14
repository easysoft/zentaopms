#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 screenModel->preparegroupdataset();
timeout=0
cid=18271

- 测试componentList属性为空的情况下，生成的默认值是否正确;属性type @group
- 测试传入componentList属性的情况下，生成的值是否正确;属性isGroup @1
- 测试传入componentList属性，生成的值是否正确;属性isGroup @1
- 测试传入componentList属性，styles有值的情况下，是否被修改。属性styles @1
- 测试status有值的情况下，是否被修改。属性status @1

*/

$screen     = new screenModelTest();
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

$components = array();
$components[] = $screen->genComponentFromData('text', 'Title1', 'Title1', $attr);
$components[] = $screen->genComponentFromData('text', '', '', $attr);
$components[] = $screen->genComponentFromData('waterpolo', 'Waterpolo1', 0.2, $attr, $waterOptions);

r($screen->preparegroupdataset($component1, array()))               && p('type')    && e('group'); // 测试componentList属性为空的情况下，生成的默认值是否正确;
r($screen->preparegroupdataset($component1, array($components[0]))) && p('isGroup') && e(1);       // 测试传入componentList属性的情况下，生成的值是否正确;
r($screen->preparegroupdataset($component1, $components))           && p('isGroup') && e(1);       // 测试传入componentList属性，生成的值是否正确;
r($screen->preparegroupdataset($component2, $components))           && p('styles')  && e(1);       // 测试传入componentList属性，styles有值的情况下，是否被修改。
r($screen->preparegroupdataset($component3, array()))               && p('status')  && e(1);       // 测试status有值的情况下，是否被修改。
