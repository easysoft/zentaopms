#!/usr/bin/env php
<?php

/**

title=测试 docZen::previewUR();
timeout=0
cid=0

- 执行docTest模块的previewURTest方法，参数是'setting', array  @1
- 执行docTest模块的previewURTest方法，参数是'setting', array  @1
- 执行docTest模块的previewURTest方法，参数是'list', array  @1
- 执行docTest模块的previewURTest方法，参数是'', array  @1
- 执行docTest模块的previewURTest方法，参数是'setting', array  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

zenData('story');
$story = zenData('story');
$story->id->range('1-10');
$story->title->range('用户需求{1-10}');
$story->product->range('1-3');
$story->type->range('requirement{10}');
$story->status->range('active{5},draft{3},closed{2}');
$story->pri->range('1-3:R');
$story->openedBy->range('admin,user1,user2');
$story->openedDate->range('2024-01-01 00:00:00,2024-01-02 00:00:00,2024-01-03 00:00:00')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$story->version->range('1');
$story->deleted->range('0');
$story->gen(10);

zenData('product');
$product = zenData('product');
$product->id->range('1-3');
$product->name->range('产品1,产品2,产品3');
$product->code->range('product1,product2,product3');
$product->type->range('normal');
$product->status->range('normal');
$product->deleted->range('0');
$product->gen(3);

su('admin');

$docTest = new docTest();

r($docTest->previewURTest('setting', array('action' => 'preview', 'product' => 1, 'condition' => 'all'), '')) && p() && e('1');

r($docTest->previewURTest('setting', array('action' => 'preview', 'product' => 2, 'condition' => 'customSearch', 'field' => array('status'), 'operator' => array('='), 'value' => array('active'), 'andor' => array('')), '')) && p() && e('1');

r($docTest->previewURTest('list', array(), '1,2,3')) && p() && e('1');

r($docTest->previewURTest('', array(), '')) && p() && e('1');

r($docTest->previewURTest('setting', array('action' => 'preview', 'product' => 999, 'condition' => 'all'), '')) && p() && e('1');