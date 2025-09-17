#!/usr/bin/env php
<?php

/**
title=检查空间各tab下数据
timeout=0
cid=0
*/
chdir(__DIR__);
include '../lib/ui/space.ui.class.php';

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
r($tester->switchTab($tabName, $tabNum)) && p('message,status') && e('我参与的tab下数据显示正确,SUCCESS');//检查[我参与的]tab下的数据

$tabName = 'cooperation';
$tabNum  = 2;
r($tester->switchTab($tabName, $tabNum)) && p('message,status') && e('协作空间tab下数据显示正确,SUCCESS');//检查[协作空间]tab下的数据

$tabName = 'public';
$tabNum  = 3;
r($tester->switchTab($tabName, $tabNum)) && p('message,status') && e('公共空间tab下数据显示正确,SUCCESS');//检查[公共空间]tab下的数据

$tabName = 'private';
$tabNum  = 1;
r($tester->switchTab($tabName, $tabNum)) && p('message,status') && e('私人空间tab下数据显示正确,SUCCESS');//检查[私人空间]tab下的数据

$tester->closeBrowser();
