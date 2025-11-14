#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printDocMyCreatedBlock();
timeout=0
cid=15256

- 测试1:当用户创建6个文档时返回数量属性count @6
- 测试2:验证返回文档的editedDate格式正确第1条的editedDate属性 @2024-11-07
- 测试3:验证文档标题正确第1条的title属性 @我创建的文档1
- 测试4:验证多次调用结果一致属性count @6
- 测试5:验证当没有创建文档时返回空属性count @0

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
$doc->title->range('我创建的文档1,我创建的文档2,我创建的文档3,我创建的文档4,我创建的文档5,我创建的文档6,我创建的文档7,我创建的文档8,我创建的文档9,我创建的文档10');
$doc->addedBy->range('admin{6},user{4}');
$doc->status->range('normal');
$doc->deleted->range('0');
$doc->vision->range('rnd');
$doc->editedDate->range('`2024-11-07 10:00:00`');
$doc->addedDate->range('`2024-11-07 09:00:00`,`2024-11-07 08:00:00`,`2024-11-07 07:00:00`,`2024-11-07 06:00:00`,`2024-11-07 05:00:00`,`2024-11-07 04:00:00`,`2024-11-07 03:00:00`,`2024-11-07 02:00:00`,`2024-11-07 01:00:00`,`2024-11-07 00:00:00`');
$doc->gen(10);

su('admin');

$blockTest = new blockZenTest();

r($blockTest->printDocMyCreatedBlockTest()) && p('count') && e('6'); // 测试1:当用户创建6个文档时返回数量
r($blockTest->printDocMyCreatedBlockTest()) && p('1:editedDate') && e('2024-11-07'); // 测试2:验证返回文档的editedDate格式正确
r($blockTest->printDocMyCreatedBlockTest()) && p('1:title') && e('我创建的文档1'); // 测试3:验证文档标题正确
r($blockTest->printDocMyCreatedBlockTest()) && p('count') && e('6'); // 测试4:验证多次调用结果一致

zenData('doc')->gen(0);

r($blockTest->printDocMyCreatedBlockTest()) && p('count') && e('0'); // 测试5:验证当没有创建文档时返回空