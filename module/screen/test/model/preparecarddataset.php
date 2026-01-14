#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 screenModel->prepareCardDataset();
timeout=0
cid=18269

- 测试value属性为空的情况下，生成的默认值是否正确;属性type @card
- 测试传入value属性的情况下，生成的值是否正确;属性type @card
- 测试传入value属性，styles有值的情况下，是否被修改。属性styles @1
- 测试status有值的情况下，是否被修改。属性status @1
- 测试request有值的情况下，是否被修改。属性request @1

*/

$screen     = new screenModelTest();
$component1 = new stdclass();
$component1->type   = 'card';
$component1->option = new stdclass();

$component2 = clone $component1;
$component2->styles = 1;

$component3 = clone $component1;
$component3->status = 1;

$component4 = clone $component1;
$component4->request = 1;

r($screen->prepareCardDataset($component1, '0')) && p('type')    && e('card'); // 测试value属性为空的情况下，生成的默认值是否正确;
r($screen->prepareCardDataset($component1, '1')) && p('type')    && e('card'); // 测试传入value属性的情况下，生成的值是否正确;
r($screen->prepareCardDataset($component2, '1')) && p('styles')  && e(1);      // 测试传入value属性，styles有值的情况下，是否被修改。
r($screen->prepareCardDataset($component3, '0')) && p('status')  && e(1);      // 测试status有值的情况下，是否被修改。
r($screen->prepareCardDataset($component4, '0')) && p('request') && e(1);      // 测试request有值的情况下，是否被修改。
