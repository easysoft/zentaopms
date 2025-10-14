#!/usr/bin/env php
<?php
/**
title=创建看板
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
zendata('kanban')->loadYaml('kanban', false, 2)->gen(0);
zendata('kanbanregion')->loadYaml('kanbanregion', false, 2)->gen(0);
zendata('kanbanlane')->loadYaml('kanbanlane', false, 2)->gen(0);
zendata('kanbancell')->loadYaml('kanbancell', false, 2)->gen(0);
zendata('kanbancolumn')->loadYaml('kanbancolumn', false, 2)->gen(0);
zendata('kanbancard')->loadYaml('kanbancard', false, 2)->gen(0);

$tester = new kanbanTester();
$tester->login();
$kanban = new stdClass();

$kanban->name = '';
r($tester->createKanban($kanban)) && p('message,status') && e('看板名称必填提示信息正确,SUCCESS');//看板名必填校验

$kanban->name = '第一个看板';
r($tester->createKanban($kanban)) && p('message,status') && e('创建看板成功,SUCCESS');//创建看板

$tester->closeBrowser();
