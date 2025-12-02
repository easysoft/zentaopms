#!/usr/bin/env php
<?php

/**

title=测试 blockZen::processBlockForRender();
timeout=0
cid=15317

- 执行blockTest模块的processBlockForRenderTest方法，参数是array 第0条的id属性 @1
- 执行$result[0]->params->count) ? $result[0]->params->count : 0 @10
- 执行blockTest模块的processBlockForRenderTest方法，参数是array 第0条的top属性 @-1
- 执行blockTest模块的processBlockForRenderTest方法，参数是array 第0条的left属性 @2
- 执行blockTest模块的processBlockForRenderTest方法，参数是array  @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$blockTest = new blockZenTest();

// 测试步骤1:正常处理单个区块,验证区块ID保持不变
$block1 = new stdClass();
$block1->id = 1;
$block1->module = 'product';
$block1->code = 'product';
$block1->params = '{"num":10}';
$block1->width = 1;
$block1->height = 3;
$block1->left = 0;
$block1->top = 0;
r($blockTest->processBlockForRenderTest(array($block1), 0)) && p('0:id') && e('1');

// 测试步骤2:验证params中的num字段被转换为count
$block1 = new stdClass();
$block1->id = 1;
$block1->module = 'product';
$block1->code = 'product';
$block1->params = '{"num":10}';
$block1->width = 1;
$block1->height = 3;
$block1->left = 0;
$block1->top = 0;
$result = $blockTest->processBlockForRenderTest(array($block1), 0);
r(isset($result[0]->params->count) ? $result[0]->params->count : 0) && p() && e('10');

// 测试步骤3:验证区块top为0时自动设置为-1
$block1 = new stdClass();
$block1->id = 1;
$block1->module = 'product';
$block1->code = 'product';
$block1->params = '{"num":10}';
$block1->width = 1;
$block1->height = 3;
$block1->left = 0;
$block1->top = 0;
r($blockTest->processBlockForRenderTest(array($block1), 0)) && p('0:top') && e('-1');

// 测试步骤4:验证width=1且left为空时自动设置为2
$block2 = new stdClass();
$block2->id = 2;
$block2->module = 'product';
$block2->code = 'product';
$block2->params = '{"count":5}';
$block2->width = 1;
$block2->height = 2;
$block2->left = '';
$block2->top = 0;
r($blockTest->processBlockForRenderTest(array($block2), 0)) && p('0:left') && e('2');

// 测试步骤5:验证多个区块同时处理,返回3个区块
$block1 = new stdClass();
$block1->id = 1;
$block1->module = 'product';
$block1->code = 'product';
$block1->params = '{"num":10}';
$block1->width = 1;
$block1->height = 3;
$block1->left = 0;
$block1->top = 0;

$block2 = new stdClass();
$block2->id = 2;
$block2->module = 'project';
$block2->code = 'execution';
$block2->params = '{"count":5}';
$block2->width = 1;
$block2->height = 2;
$block2->left = 0;
$block2->top = 0;

$block3 = new stdClass();
$block3->id = 3;
$block3->module = 'common';
$block3->code = 'guide';
$block3->params = '{"num":15}';
$block3->width = 2;
$block3->height = 3;
$block3->left = 0;
$block3->top = 0;
r(count($blockTest->processBlockForRenderTest(array($block1, $block2, $block3), 0))) && p() && e('3');