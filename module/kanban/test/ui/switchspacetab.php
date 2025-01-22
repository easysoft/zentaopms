#!/usr/bin/env php
<?php
chdir(__DIR__);
include '../lib/space.ui.class.php';

$kanbanspace = zenData('kanbanspace');
$kanbanspace->id->range('1-6');
$kanbanspace->name->range('协作空间01,协作空间02,公开空间01,公开空间02,公共空间03,私人空间01');
$kanbanspace->type->range('cooperation{2},public{3},private{1}');
$kanbanspace->owner->range('admin');
$kanbanspace->team->range(',admin');
$kanbanspace->acl->range('open');
$kanbanspace->status->range('active');
$kanbanspace->createdBy->range('admin');
$kanbanspace->gen(6);
$tester = new spaceTester();
$tester->login();
$tabName = 'involved';
$tabNum  = 6;
$tester->closeBrowser();
