#!/usr/bin/env php
<?php

/**
title=编辑空间
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

$space = new stdClass();
$space->name = '';
r($tester->editSpace($space)) && p('message,status') && e('空间名称必填提示信息正确,SUCCESS');//空间名必填校验

$space->name = '协作空间-编辑';
r($tester->editSpace($space)) && p('message,status') && e('编辑空间成功,SUCCESS');//修改空间名称

$tester->closeBrowser();
