#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printDocCollectListBlock();
timeout=0
cid=0

- 测试步骤1:验证返回6个收藏数最高的文档属性count @6
- 测试步骤2:验证第1个文档的收藏数最高第1条的collects属性 @100
- 测试步骤3:验证第2个文档的收藏数第2条的collects属性 @90
- 测试步骤4:验证文档按收藏数倒序排列第1条的title属性 @高收藏文档1
- 测试步骤5:验证所有返回的文档收藏数都大于0第6条的collects属性 @50

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
$doc->id->range('1-20');
$doc->lib->range('1');
$doc->title->range('高收藏文档1,高收藏文档2,高收藏文档3,高收藏文档4,高收藏文档5,高收藏文档6,中收藏文档1,中收藏文档2,中收藏文档3,中收藏文档4,低收藏文档1,低收藏文档2,低收藏文档3,低收藏文档4,零收藏文档1,零收藏文档2,零收藏文档3,零收藏文档4,零收藏文档5,零收藏文档6');
$doc->status->range('normal');
$doc->deleted->range('0');
$doc->vision->range('rnd');
$doc->collects->range('100,90,80,70,60,50,40,30,20,15,10,9,8,7,0,0,0,0,0,0');
$doc->editedDate->range('`2024-11-07 10:00:00`');
$doc->gen(20);

su('admin');

$blockTest = new blockZenTest();

r($blockTest->printDocCollectListBlockTest()) && p('count') && e('6'); // 测试步骤1:验证返回6个收藏数最高的文档
r($blockTest->printDocCollectListBlockTest()) && p('1:collects') && e('100'); // 测试步骤2:验证第1个文档的收藏数最高
r($blockTest->printDocCollectListBlockTest()) && p('2:collects') && e('90'); // 测试步骤3:验证第2个文档的收藏数
r($blockTest->printDocCollectListBlockTest()) && p('1:title') && e('高收藏文档1'); // 测试步骤4:验证文档按收藏数倒序排列
r($blockTest->printDocCollectListBlockTest()) && p('6:collects') && e('50'); // 测试步骤5:验证所有返回的文档收藏数都大于0