#!/usr/bin/env php
<?php
declare(strict_types=1);
/**
title=套件列表测试
timeout=0
cid=72
*/
chdir(__DIR__);
include '../lib/ui/browse.ui.class.php';

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
$testsuite->id->range('1-2');
$testsuite->product->range('1');
$testsuite->name->range('1-2')->prefix('公开套件,私有套件');
$testsuite->type->range('public,private');
$testsuite->addedBy->range('admin');
$testsuite->gen(2);

$action = zenData('action');
$action->id->range('1-2');
$action->objectType->range('product,testsuite');
$action->objectID->range('1,0');
$action->product->range('`,1,`,`,0,`');
$action->project->range('0');
$action->execution->range('0');
$action->actor->range('admin');
$action->action->range('opened');
$action->read->range('0');
$action->vision->range('rnd');
$action->gen(2);

$tester = new browseTestSuiteTester();
$tester->login();

r($tester->browseTestSuite()) && p('message,status') && e('测试套件列表测试成功,SUCCESS');

$tester->closeBrowser();
