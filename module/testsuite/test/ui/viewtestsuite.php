#!/usr/bin/env php
<?php
declare(strict_types=1);
/**
title=创建套件测试
timeout=0
cid=70
*/
chdir(__DIR__);
include '../lib/ui/view.ui.class.php';

$product = zenData('product');
$product->id->range('1');
$product->program->range('0');
$product->name->range('产品1');
$product->shadow->range('0');
$product->bind->range('0');
$product->acl->range('open');
$product->createdBy->range('admin');
$product->vision->range('rnd');
$product->gen(1);

$testsuite = zenData('testsuite');
$testsuite->id->range('1');
$testsuite->product->range('1');
$testsuite->name->range('套件1');
$testsuite->type->range('public');
$testsuite->addedBy->range('admin');
$testsuite->gen(1);

$action = zenData('action');
$action->id->range('1-2');
$action->objectType->range('product,testsuite');
$action->objectID->range('1');
$action->product->range('`,1,`,`,1,`');
$action->project->range('0');
$action->execution->range('0');
$action->actor->range('admin');
$action->action->range('opened');
$action->read->range('0');
$action->vision->range('rnd');
$action->gen(2);

$tester = new viewTester();
$tester->login();

r($tester->view()) && p('message,status') && e('套件详情页测试成功,SUCCESS');

$tester->closeBrowser();
