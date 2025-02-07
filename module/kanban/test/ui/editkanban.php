#!/usr/bin/env php
<?php

/**
title=编辑看板
timeout=0
cid=0
*/
chdir(__DIR__);
include '../lib/kanban.ui.class.php';

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

$kanbanData = zenData('kanban');
$kanbanData->id->range('1');
$kanbanData->space->range('1');
$kanbanData->name->range('看板-01');
$kanbanData->owner->range('admin');
$kanbanData->team->range(',admin');
$kanbanData->acl->range('open');
$kanbanData->status->range('active');
$kanbanData->order->range('1');
$kanbanData->object->range('plans,releases,builds,executions,cards');
$kanbanData->gen(1);


$tester->closeBrowser();
