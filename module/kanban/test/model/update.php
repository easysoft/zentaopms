#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanban')->gen(5);

/**

title=测试 kanbanModel->update();
timeout=0
cid=1

- 正常编辑看板，查看编辑后的数据
 - 属性name @测试编辑看板1
 - 属性space @1
 - 属性owner @po15
 - 属性whitelist @,user3,po15
- 正常编辑看板，查看编辑后的数据
 - 属性name @测试编辑看板2
 - 属性space @2
 - 属性owner @po16
 - 属性whitelist @user4
- 正常编辑看板，查看编辑后的数据
 - 属性name @测试编辑看板3
 - 属性space @3
 - 属性owner @po17
 - 属性whitelist @,user4,po16
- 编辑名字为空的看板，查看错误信息第name条的0属性 @『看板名称』不能为空。

*/

$kanban1 = new stdclass();
$kanban1->space = 1;
$kanban1->name  = '测试编辑看板1';
$kanban1->owner = 'po15';
$kanban1->team  = 'user3';
$kanban1->desc  = '测试编辑看板1的描述';

$kanban2 = new stdclass();
$kanban2->space     = 2;
$kanban2->name      = '测试编辑看板2';
$kanban2->owner     = 'po16';
$kanban2->whitelist = 'user4';
$kanban2->desc      = '测试编辑看板2的描述';

$kanban3 = new stdclass();
$kanban3->space = 3;
$kanban3->name  = '测试编辑看板3';
$kanban3->owner = 'po17';
$kanban3->team  = 'user5';
$kanban3->desc  = '测试编辑看板3的描述';

$kanban4 = new stdclass();
$kanban4->space = 3;
$kanban4->name  = '';
$kanban4->owner = 'po17';
$kanban4->team  = 'user5';
$kanban4->desc  = '测试编辑没有名字的看板的描述';

$kanban = new kanbanTest();

r($kanban->updateTest(1, $kanban1)) && p('name|space|owner|whitelist', '|') && e('测试编辑看板1|1|po15|,user3,po15'); // 正常编辑看板，查看编辑后的数据
r($kanban->updateTest(2, $kanban2)) && p('name|space|owner|whitelist', '|') && e('测试编辑看板2|2|po16|user4');       // 正常编辑看板，查看编辑后的数据
r($kanban->updateTest(3, $kanban3)) && p('name|space|owner|whitelist', '|') && e('测试编辑看板3|3|po17|,user4,po16'); // 正常编辑看板，查看编辑后的数据
r($kanban->updateTest(4, $kanban4)) && p('name:0')                          && e('『看板名称』不能为空。');           // 编辑名字为空的看板，查看错误信息