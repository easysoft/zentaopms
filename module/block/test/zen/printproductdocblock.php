#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printProductDocBlock();
timeout=0
cid=0

- 测试默认type参数(involved)
 - 属性type @involved
 - 属性docGroupCount @3
- 测试指定type为all
 - 属性type @all
 - 属性docGroupCount @3
- 测试block对象设置count为5
 - 属性type @involved
 - 属性docGroupCount @3
- 测试多个产品多个文档的情况
 - 属性type @involved
 - 属性productsCount @0
- 测试验证users数据加载属性usersCount @6

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('user')->gen(5);

$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,产品3,产品4,产品5');
$product->status->range('normal');
$product->acl->range('open');
$product->deleted->range('0');
$product->gen(5);

$doclib = zenData('doclib');
$doclib->id->range('1-3');
$doclib->name->range('文档库1,文档库2,文档库3');
$doclib->type->range('product');
$doclib->product->range('1-3');
$doclib->acl->range('open');
$doclib->deleted->range('0');
$doclib->gen(3);

$doc = zenData('doc');
$doc->id->range('1-15');
$doc->product->range('1{5},2{5},3{5}');
$doc->lib->range('1{5},2{5},3{5}');
$doc->title->range('文档1,文档2,文档3,文档4,文档5,文档6,文档7,文档8,文档9,文档10,文档11,文档12,文档13,文档14,文档15');
$doc->type->range('text');
$doc->status->range('normal');
$doc->acl->range('open');
$doc->addedBy->range('admin');
$doc->deleted->range('0');
$doc->gen(15);

$team = zenData('team');
$team->root->range('1-2');
$team->type->range('project');
$team->account->range('admin');
$team->gen(2);

su('admin');

$block = new stdClass();
$block->params = new stdClass();
$block->params->count = 15;

$blockWithCount5 = new stdClass();
$blockWithCount5->params = new stdClass();
$blockWithCount5->params->count = 5;

$blockTest = new blockZenTest();

r($blockTest->printProductDocBlockTest($block, array('type' => 'involved'))) && p('type,docGroupCount') && e('involved,3'); // 测试默认type参数(involved)
r($blockTest->printProductDocBlockTest($block, array('type' => 'all'))) && p('type,docGroupCount') && e('all,3'); // 测试指定type为all
r($blockTest->printProductDocBlockTest($blockWithCount5, array('type' => 'involved'))) && p('type,docGroupCount') && e('involved,3'); // 测试block对象设置count为5
r($blockTest->printProductDocBlockTest($block, array('type' => 'involved'))) && p('type,productsCount') && e('involved,0'); // 测试多个产品多个文档的情况
r($blockTest->printProductDocBlockTest($block, array('type' => 'involved'))) && p('usersCount') && e('6'); // 测试验证users数据加载