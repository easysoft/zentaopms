#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 screenModel->prepareBoxDataset();
timeout=0
cid=18268

- 测试data属性为空的情况下，生成的默认值是否正确;属性type @box
- 测试传入data属性的情况下，生成的值是否正确;属性type @box
- 测试传入data属性，styles有值的情况下，是否被修改。属性styles @1
- 测试status有值的情况下，是否被修改。属性status @1
- 测试request有值的情况下，是否被修改。属性request @1

*/

$screen     = new screenModelTest();
$component1 = new stdclass();
$component1->type   = 'box';
$component1->option = new stdclass();

$component2 = clone $component1;
$component2->styles = 1;

$component3 = clone $component1;
$component3->status = 1;

$component4 = clone $component1;
$component4->request = 1;

r($screen->prepareBoxDataset($component1, array()))            && p('type')    && e('box'); // 测试data属性为空的情况下，生成的默认值是否正确;
r($screen->prepareBoxDataset($component1, array(0,1,2,3,4,5))) && p('type')    && e('box'); // 测试传入data属性的情况下，生成的值是否正确;
r($screen->prepareBoxDataset($component2, array(0,1,2,3,4,5))) && p('styles')  && e(1);     // 测试传入data属性，styles有值的情况下，是否被修改。
r($screen->prepareBoxDataset($component3, array()))            && p('status')  && e(1);     // 测试status有值的情况下，是否被修改。
r($screen->prepareBoxDataset($component4, array()))            && p('request') && e(1);     // 测试request有值的情况下，是否被修改。
