#!/usr/bin/env php
<?php

/**
title=关闭/激活看板
timeout=0
cid=0
*/
chdir(__DIR__);
include '../lib/ui/kanban.ui.class.php';

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
$kanban->status->range('active');
$kanban->order->range('1');
$kanban->object->range('plans,releases,builds,executions,cards');
$kanban->gen(1);

$tester = new kanbanTester();
$tester->login();

r($tester->closeKanban())    && p('message,status') && e('关闭看板成功,SUCCESS');//关闭看板
r($tester->activateKanban()) && p('message,status') && e('激活看板成功,SUCCESS');//激活看板

$tester->closeBrowser();
