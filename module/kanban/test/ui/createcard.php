#!/usr/bin/env php
<?php

/**
title=创建卡片
timeout=0
cid=0
*/
chdir(__DIR__);
include '../lib/card.ui.class.php';

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

$tester->closeBrowser();
