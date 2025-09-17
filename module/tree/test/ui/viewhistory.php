#!/usr/bin/env php
<?php

/**
title=查看维护模块的历史记录
timeout=0
cid=1
 */

chdir(__DIR__);
include '../lib/ui/viewhistory.ui.class.php';

$product = zenData('product');
$product->id->range('1-100');
$product->program->range('0');
$product->name->range('产品1, 产品2, 产品3, 产品4');
$product->type->range('normal');
$product->gen(1);

$module = zenData('module');
$module->id->range('1-100');
$module->root->range('1');
$module->name->range('模块1, 模块2, 子模块1, 子模块2');
$module->parent->range('0{2}, 1{2}');
$module->path->range('`,1,`, `,2,`, `,1,3,`, `,1,4,`');
$module->grade->range('1{2}, 2{2}');
$module->type->range('story');
$module->deleted->range('0{3}, 1');
$module->gen(4);

$action = zenData('action');
$action->id->range('1-100');
$action->objectType->range('module');
$action->objectID->range('1{3}, 4');
$action->product->range('1');
$action->action->range('created{2}, edited, deleted');
$action->extra->range('`1,2`, `3,4`, 3, 1');
$action->gen(4);

zenData('history')->gen(0);

$actionrecent = zenData('actionrecent');
$actionrecent->id->range('1-100');
$actionrecent->objectType->range('module');
$actionrecent->objectID->range('1{3}, 4');
$actionrecent->product->range('1');
$actionrecent->action->range('created{2}, edited, deleted');
$actionrecent->extra->range('`1,2`, `3,4`, 3, 1');
$actionrecent->gen(4);

$tester = new viewhistoryTester();
$tester->login();

r($tester->checkHistory('historya', 'created')) && p('status,message') && e('SUCCESS,历史记录正确');
r($tester->checkHistory('historyb', 'created')) && p('status,message') && e('SUCCESS,历史记录正确');
r($tester->checkHistory('historyc', 'edited'))  && p('status,message') && e('SUCCESS,历史记录正确');
r($tester->checkHistory('historyd', 'deleted')) && p('status,message') && e('SUCCESS,历史记录正确');
$tester->closeBrowser();
