#!/usr/bin/env php
<?php
/**

title=设计列表检查测试
timeout=0
cid=10

- 执行tester模块的checkMenu方法，参数是'hlds', '1' 测试结果 @hlds菜单下显示数据正确
- 执行tester模块的checkMenu方法，参数是'dds', '1' 测试结果 @dds菜单下显示数据正确
- 执行tester模块的checkMenu方法，参数是'dbds', '1' 测试结果 @dbds菜单下显示数据正确
- 执行tester模块的checkMenu方法，参数是'ads', '1' 测试结果 @ads菜单下显示数据正确
- 执行tester模块的checkMenu方法，参数是'all', '4' 测试结果 @all菜单下显示数据正确
- 执行tester模块的switchProduct方法，参数是'firstProduct', '2' 测试结果 @切换firstProduct查看设计数据成功
- 执行tester模块的switchProduct方法，参数是'secondProduct', '2' 测试结果 @切换secondProduct查看设计数据成功

*/
chdir(__DIR__);
include '../lib/ui/browse.ui.class.php';

$project = zendata('project');
$project->id->range('1');
$project->project->range('0');
$project->model->range('waterfall');
$project->type->range('project');
$project->parent->range('0');
$project->auth->range('extend');
$project->grade->range('1');
$project->name->range('瀑布项目1');
$project->path->range('`,1,`');
$project->begin->range('(-2M)-(-M):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+2M)-(+3M):1D')->type('timestamp')->format('YY/MM/DD');
$project->acl->range('open');
$project->gen(1);

$product = zenData('product');
$product->id->range('1-2');
$product->name->range('产品1, 产品2');
$product->type->range('normal');
$product->gen(2);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1');
$projectProduct->product->range('1{1}, 2{1}');
$projectProduct->gen(2);

$design = zendata('design');
$design->id->range('1-4');
$design->project->range('1{4}');
$design->product->range('1{2}, 2{2}');
$design->name->range('概要设计1, 详细设计1, 数据库设计1, 接口设计1');
$design->type->range('HLDS, DDS, DBDS, ADS');
$design->gen(4);

$tester = new browseTester();
$tester->login();

/* 检查各个菜单下显示数据 */
r($tester->checkMenu('hlds', '1')) && p('message') && e('hlds菜单下显示数据正确');
r($tester->checkMenu('dds', '1'))  && p('message') && e('dds菜单下显示数据正确');
r($tester->checkMenu('dbds', '1')) && p('message') && e('dbds菜单下显示数据正确');
r($tester->checkMenu('ads', '1'))  && p('message') && e('ads菜单下显示数据正确');
r($tester->checkMenu('all', '4'))  && p('message') && e('all菜单下显示数据正确');

/* 检查不同产品下显示数据 */
r($tester->switchProduct('firstProduct', '2'))  && p('message') && e('切换firstProduct查看设计数据成功');
r($tester->switchProduct('secondProduct', '2')) && p('message') && e('切换secondProduct查看设计数据成功');

$tester->closeBrowser();
