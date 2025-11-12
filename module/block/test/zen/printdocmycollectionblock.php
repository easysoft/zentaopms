#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printDocMyCollectionBlock();
timeout=0
cid=0

- 测试1:当用户有6个收藏文档时返回数量属性count @6
- 测试2:验证返回文档的editedDate格式正确第1条的editedDate属性 @2024-11-07
- 测试3:验证文档标题正确第1条的title属性 @我的文档1
- 测试4:验证多次调用结果一致属性count @6
- 测试5:验证当没有收藏文档时返回空属性count @0

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
$doc->title->range('我的文档1,我的文档2,我的文档3,我的文档4,我的文档5,我的文档6,我的文档7,我的文档8,我的文档9,我的文档10');
$doc->status->range('normal');
$doc->deleted->range('0');
$doc->vision->range('rnd');
$doc->editedDate->range('`2024-11-07 10:00:00`');
$doc->gen(10);

$docaction = zenData('docaction');
$docaction->id->range('1-6');
$docaction->doc->range('1-6');
$docaction->action->range('collect');
$docaction->actor->range('admin');
$docaction->date->range('`2024-11-07 10:00:00`');
$docaction->gen(6);

su('admin');

$blockTest = new blockZenTest();

r($blockTest->printDocMyCollectionBlockTest()) && p('count') && e('6'); // 测试1:当用户有6个收藏文档时返回数量
r($blockTest->printDocMyCollectionBlockTest()) && p('1:editedDate') && e('2024-11-07'); // 测试2:验证返回文档的editedDate格式正确
r($blockTest->printDocMyCollectionBlockTest()) && p('1:title') && e('我的文档1'); // 测试3:验证文档标题正确
r($blockTest->printDocMyCollectionBlockTest()) && p('count') && e('6'); // 测试4:验证多次调用结果一致

zenData('docaction')->gen(0);

r($blockTest->printDocMyCollectionBlockTest()) && p('count') && e('0'); // 测试5:验证当没有收藏文档时返回空