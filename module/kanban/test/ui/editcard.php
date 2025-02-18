#!/usr/bin/env php
<?php

/**
title=编辑卡片
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
