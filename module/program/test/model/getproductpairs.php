#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/program.unittest.class.php';
su('admin');

/* Create program data. */
$program = zenData('project');
$program->id->range('1');
$program->name->range('项目集1');
$program->type->range('program');
$program->grade->range('1');
$program->path->range('1')->prefix(',')->postfix(',');
$program->begin->range('20220112 000000:0')->type('timestamp')->format('YYYY-MM-DD');
$program->end->range('20220212 000000:0')->type('timestamp')->format('YYYY-MM-DD');
$program->gen(1);

/* Create product data. */
$product = zenData('product');
$product->id->range('1-10');
$product->name->range('1-10')->prefix('产品');
$product->program->range('1');
$product->type->range('normal');
$product->status->range('normal,closed');
$product->vision->range('rnd');
$product->gen(10);

/**

title=测试 programModel::getProductPairs();
timeout=0
cid=17693

- 获取项目集1下的所有产品数量 @10
- 根据项目集ID获取关联产品名字属性2 @产品2
- 获取项目集1下的未关闭的产品数量 @5
- 根据项目或项目集ID获取关联产品详情属性3 @产品3
- 根据项目或项目集ID获取关联产品详情属性4 @~~

*/

$programTester = new programTest();

$products1 = $programTester->getProductPairsTest(1, 'assign', 'all');
$products2 = $programTester->getProductPairsTest(1, 'assign', 'noclosed');

r(count($products1)) && p()    && e('10');    // 获取项目集1下的所有产品数量
r($products1)        && p('2') && e('产品2'); // 根据项目集ID获取关联产品名字
r(count($products2)) && p()    && e('5');     // 获取项目集1下的未关闭的产品数量
r($products2)        && p('3') && e('产品3'); // 根据项目或项目集ID获取关联产品详情
r($products2)        && p('4') && e('~~');      // 根据项目或项目集ID获取关联产品详情