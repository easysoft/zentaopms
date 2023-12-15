#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanbancell')->gen(1);
zdTable('kanbancard')->gen(1);

/**

title=测试 kanbanModel->batchCreateCard();
timeout=0
cid=1

- 批量创建正常的卡片
 - 属性kanban @1
 - 属性column @1
 - 属性lane @1
 - 属性type @common
 - 属性cards @,0,1,2,801,
- 批量创建卡片，其中一个没有名称第name条的0属性 @『卡片名称』不能为空。
- 批量创建卡片 预计为负数属性estimate[1] @预计不能为负数!
- 批量创建卡片 开始时间大于结束时间属性end[1] @"截止日期"不能小于"预计开始"!

*/

$normalCard = new stdClass();
$normalCard->name       = '批量创建卡片1';
$normalCard->lane       = 1;
$normalCard->assignedTo = 'admin,user10';
$normalCard->pri        = 1;
$normalCard->estimate   = 10;
$normalCard->begin      = '2023-01-01';
$normalCard->end        = '2023-01-30';
$normalCard->desc       = '描述1';

$emptyNameCard = new stdClass();
$emptyNameCard->name       = '';
$emptyNameCard->lane       = 1;
$emptyNameCard->assignedTo = 'admin,user10';
$emptyNameCard->pri        = 1;
$emptyNameCard->estimate   = 10;
$emptyNameCard->begin      = '2023-01-01';
$emptyNameCard->end        = '2023-01-30';

$estimate = new stdClass();
$estimate->name       = '批量创建卡片2';
$estimate->lane       = 1;
$estimate->assignedTo = 'admin,user10';
$estimate->pri        = 1;
$estimate->estimate   = -10;
$estimate->begin      = '2023-01-01';
$estimate->end        = '2023-01-30';

$beginGtEnd = new stdClass();
$beginGtEnd->name       = '批量创建卡片3';
$beginGtEnd->lane       = 1;
$beginGtEnd->assignedTo = 'admin,user10';
$beginGtEnd->pri        = 1;
$beginGtEnd->estimate   = 10;
$beginGtEnd->begin      = '2023-01-30';
$beginGtEnd->end        = '2023-01-01';

$cards1[] = $normalCard;

$cards2[] = $normalCard;
$cards2[] = $emptyNameCard;

$cards3[] = $normalCard;
$cards3[] = $estimate;

$cards4[] = $normalCard;
$cards4[] = $beginGtEnd;

$kanban = new kanbanTest();

r($kanban->batchCreateCardTest($cards1))     && p('kanban|column|lane|type|cards', '|') && e('1|1|1|common|,0,1,2,801,');  // 批量创建正常的卡片
r($kanban->batchCreateCardTest($cards2))     && p('name:0')                             && e('『卡片名称』不能为空。');         // 批量创建卡片，其中一个没有名称
r($kanban->batchCreateCardTest($cards3))     && p('estimate[1]')                        && e('预计不能为负数!');                // 批量创建卡片 预计为负数
r($kanban->batchCreateCardTest($cards4))     && p('end[1]')                             && e('"截止日期"不能小于"预计开始"!');  // 批量创建卡片 开始时间大于结束时间