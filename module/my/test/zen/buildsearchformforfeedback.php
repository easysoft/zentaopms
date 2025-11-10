#!/usr/bin/env php
<?php

/**

title=测试 myZen::buildSearchFormForFeedback();
timeout=0
cid=0

- 执行myZenTest模块的buildSearchFormForFeedbackTest方法，参数queryID为0，orderBy为id_desc >> 期望hasActionURL为1
- 执行myZenTest模块的buildSearchFormForFeedbackTest方法，参数queryID为1，orderBy为id_desc >> 期望queryID为1
- 执行myZenTest模块的buildSearchFormForFeedbackTest方法，参数queryID为0，orderBy为id_asc >> 期望hasActionURL为1
- 执行myZenTest模块的buildSearchFormForFeedbackTest方法，参数queryID为100，orderBy为openedDate_desc >> 期望queryID为100
- 执行myZenTest模块的buildSearchFormForFeedbackTest方法，参数queryID为5，orderBy为status_desc >> 期望queryID为5且hasActionURL为1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$product = zenData('product');
$product->id->range('1-10');
$product->name->range('产品1,产品2,产品3,产品4,产品5,产品6,产品7,产品8,产品9,产品10');
$product->code->range('code1-code10');
$product->type->range('normal');
$product->status->range('normal');
$product->deleted->range('0');
$product->gen(10);

$module = zenData('module');
$module->id->range('1-10');
$module->name->range('模块1,模块2,模块3,模块4,模块5,模块6,模块7,模块8,模块9,模块10');
$module->type->range('feedback');
$module->parent->range('0');
$module->grade->range('1');
$module->deleted->range('0');
$module->gen(10);

su('admin');

global $app;
$app->rawMethod = 'work';

$myTest = new myZenTest();

r($myTest->buildSearchFormForFeedbackTest(0, 'id_desc')) && p('hasActionURL') && e('1');
r($myTest->buildSearchFormForFeedbackTest(1, 'id_desc')) && p('queryID') && e('1');
r($myTest->buildSearchFormForFeedbackTest(0, 'id_asc')) && p('hasActionURL') && e('1');
r($myTest->buildSearchFormForFeedbackTest(100, 'openedDate_desc')) && p('queryID') && e('100');
r($myTest->buildSearchFormForFeedbackTest(5, 'status_desc')) && p('queryID,hasActionURL') && e('5,1');
