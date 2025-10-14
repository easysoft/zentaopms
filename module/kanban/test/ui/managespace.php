#!/usr/bin/env php
<?php

/**
title=关闭/激活/删除空间
timeout=0
cid=0
*/
chdir(__DIR__);
include '../lib/ui/space.ui.class.php';

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

$tester = new spaceTester();
$tester->login();

r($tester->closeSpace())    && p('message,status') && e('关闭空间成功,SUCCESS');//关闭空间
r($tester->activateSpace()) && p('message,status') && e('激活空间成功,SUCCESS');//激活空间
r($tester->deleteSpace())   && p('message,status') && e('删除空间成功,SUCCESS');//删除空间

$tester->closeBrowser();
