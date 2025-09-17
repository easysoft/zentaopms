#!/usr/bin/env php
<?php
/**
title=创建空间
timeout=0
cid=0
*/
chdir(__DIR__);
include '../lib/ui/space.ui.class.php';
$tester = new spaceTester();
$tester->login();
$space = new stdClass();

$space->name = '';
r($tester->createSpace('cooperation', $space)) && p('message,status') && e('空间名称必填提示信息正确,SUCCESS');//空间名必填校验

$space->name = '私人空间-新建';
r($tester->createSpace('private', $space)) && p('message,status') && e('创建空间成功,SUCCESS');//创建私人空间

$space->name  = '协作空间-新建';
$space->owner = 'admin';
r($tester->createSpace('cooperation', $space)) && p('message,status') && e('创建空间成功,SUCCESS');//创建协作空间

$space->name  = '公共空间-新建';
$space->owner = 'admin';
r($tester->createSpace('public', $space)) && p('message,status') && e('创建空间成功,SUCCESS');//创建公共空间

$tester->closeBrowser();
