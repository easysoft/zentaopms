#!/usr/bin/env php
<?php

/**
title=编辑卡片
timeout=0
cid=0
*/
chdir(__DIR__);
include '../lib/ui/card.ui.class.php';

$kanbanspace = zenData('kanbanspace');
$kanbanspace->id->range('1');
$kanbanspace->name->range('协作空间-A');
$kanbanspace->type->range('cooperation');
$kanbanspace->owner->range('admin');
$kanbanspace->team->range(',admin');
$kanbanspace->acl->range('open');
$kanbanspace->status->range('active');
$kanbanspace->createdBy->range('admin');
$kanbanspace->gen(1);

$kanban = zenData('kanban');
$kanban->id->range('1');
$kanban->space->range('1');
$kanban->name->range('看板-01');
$kanban->owner->range('admin');
$kanban->team->range(',admin');
$kanban->acl->range('open');
$kanban->archived->range('1');
$kanban->performable->range('1');
$kanban->status->range('active');
$kanban->order->range('1');
$kanban->object->range('`plans,releases,builds,executions,cards`');
$kanban->gen(1);

$kanbanregion = zenData('kanbanregion');
$kanbanregion->id->range('1');
$kanbanregion->space->range('1');
$kanbanregion->name->range('区域AAA');
$kanbanregion->order->range('1');
$kanbanregion->gen(1);

$kanbanlane = zenData('kanbanlane');
$kanbanlane->id->range('1');
$kanbanlane->execution->range('0');
$kanbanlane->type->range('common');
$kanbanlane->region->range('1');
$kanbanlane->group->range('1');
$kanbanlane->groupby->range('');
$kanbanlane->name->range('默认泳道');
$kanbanlane->color->range('#7ec5ff');
$kanbanlane->order->range('1');
$kanbanlane->gen(1);

$kanbancell = zenData('kanbancell');
$kanbancell->id->range('1-4');
$kanbancell->kanban->range('1');
$kanbancell->lane->range('1');
$kanbancell->column->range('1-4');
$kanbancell->type->range('common');
$kanbancell->gen(4);

$kanbancolumn = zenData('kanbancolumn');
$kanbancolumn->id->range('1-4');
$kanbancolumn->parent->range('0');
$kanbancolumn->type->range('column1,column2,column3,column4');
$kanbancolumn->region->range('1');
$kanbancolumn->group->range('1');
$kanbancolumn->name->range('未开始,进行中,已完成,已关闭');
$kanbancolumn->color->range('#333');
$kanbancolumn->limit->range('100');
$kanbancolumn->order->range('1-4');
$kanbancolumn->archived->range('0');
$kanbancolumn->gen(4);

$kanbangroup = zenData('kanbangroup');
$kanbangroup->id->range('1');
$kanbangroup->kanban->range('1');
$kanbangroup->region->range('1');
$kanbangroup->order->range('1');
$kanbangroup->gen(1);

$kanbancard = zenData('kanbancard');
$kanbancard->id->range('1');
$kanbancard->kanban->range('1');
$kanbancard->region->range('1');
$kanbancard->group->range('1');
$kanbancard->fromID->range('0');
$kanbancard->name->range('卡片01');
$kanbancard->status->range('doing');
$kanbancard->gen(1);

$tester = new cardTester();
$tester->login();

$kanbanurl['kanbanID'] = 1;

$card = new stdClass();
$card->name = '';
r($tester->editCard($kanbanurl, $card)) && p('message,status') && e('卡片名称必填提示正确,SUCCESS');//卡片名称必填校验

$card->name  = '编辑卡片';
$card->begin = '2025-03-01';
$card->end   = '2025-02-18';
r($tester->editCard($kanbanurl, $card)) && p('message,status') && e('卡片日期校验提示正确,SUCCESS');//卡片日期校验

$card->name  = '编辑卡片';
$card->begin = '2025-03-01';
$card->end   = '2025-03-30';
r($tester->editCard($kanbanurl, $card)) && p('message,status') && e('编辑卡片成功,SUCCESS');//编辑卡片

$tester->closeBrowser();
