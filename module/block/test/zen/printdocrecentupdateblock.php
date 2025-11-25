#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printDocRecentUpdateBlock();
timeout=0
cid=15257

- 测试当有10条文档时返回最多6条属性count @6
- 测试返回文档的editedDate格式正确第1条的editedDate属性 @2024-11-10
- 测试返回文档的标题正确第1条的title属性 @文档1
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
$doc->title->range('文档1,文档2,文档3,文档4,文档5,文档6,文档7,文档8,文档9,文档10');
$doc->status->range('normal');
$doc->deleted->range('0');
$doc->vision->range('rnd');
$doc->editedDate->range('`2024-11-10 10:00:00`,`2024-11-09 10:00:00`,`2024-11-08 10:00:00`,`2024-11-07 10:00:00`,`2024-11-06 10:00:00`,`2024-11-05 10:00:00`,`2024-11-04 10:00:00`,`2024-11-03 10:00:00`,`2024-11-02 10:00:00`,`2024-11-01 10:00:00`');
$doc->gen(10);

su('admin');

$blockTest = new blockZenTest();

r($blockTest->printDocRecentUpdateBlockTest()) && p('count') && e('6'); // 测试当有10条文档时返回最多6条
r($blockTest->printDocRecentUpdateBlockTest()) && p('1:editedDate') && e('2024-11-10'); // 测试返回文档的editedDate格式正确
r($blockTest->printDocRecentUpdateBlockTest()) && p('1:title') && e('文档1'); // 测试返回文档的标题正确
r($blockTest->printDocRecentUpdateBlockTest()) && p('count') && e('6'); // 测试多次调用结果一致

zenData('doc')->gen(0);

r($blockTest->printDocRecentUpdateBlockTest()) && p('count') && e('0'); // 测试当没有文档时返回空