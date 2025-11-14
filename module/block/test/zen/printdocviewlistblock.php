#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printDocViewListBlock();
timeout=0
cid=15259

- 测试当有10条文档时按浏览量降序返回最多6条属性count @6
- 测试返回文档按浏览量降序排列第1条的views属性 @100
- 测试返回文档的标题正确第1条的title属性 @高浏览文档1
- 测试多次调用结果一致属性count @6
- 测试当没有文档时返回空属性count @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('user')->gen(5);

$doclib = zenData('doclib');
$doclib->id->range('1');
$doclib->name->range('测试文档库');
$doclib->type->range('custom');
$doclib->acl->range('open');
$doclib->deleted->range('0');
$doclib->gen(1);

$doc = zenData('doc');
$doc->id->range('1-10');
$doc->lib->range('1');
$doc->title->range('高浏览文档1,高浏览文档2,高浏览文档3,高浏览文档4,高浏览文档5,高浏览文档6,中浏览文档1,中浏览文档2,低浏览文档1,低浏览文档2');
$doc->status->range('normal');
$doc->deleted->range('0');
$doc->vision->range('rnd');
$doc->views->range('100,95,90,85,80,75,50,45,10,5');
$doc->gen(10);

su('admin');

$blockTest = new blockZenTest();

r($blockTest->printDocViewListBlockTest()) && p('count') && e('6'); // 测试当有10条文档时按浏览量降序返回最多6条
r($blockTest->printDocViewListBlockTest()) && p('1:views') && e('100'); // 测试返回文档按浏览量降序排列
r($blockTest->printDocViewListBlockTest()) && p('1:title') && e('高浏览文档1'); // 测试返回文档的标题正确
r($blockTest->printDocViewListBlockTest()) && p('count') && e('6'); // 测试多次调用结果一致

zenData('doc')->gen(0);

r($blockTest->printDocViewListBlockTest()) && p('count') && e('0'); // 测试当没有文档时返回空