#!/usr/bin/env php
<?php

/**
title=维护产品模块
timeout=0
cid=1
 */

chdir(__DIR__);
include '../lib/browse.ui.class.php';

$product = zenData('product');
$product->id->range('1');
$product->name->range('产品1');
$product->type->range('normal');
$product->gen(1);

$module = zenData('module');
$module->gen(0, true);

$tester = new browseTester();
$tester->login();

r($tester->createModule('模块 1'))             && p('status,message') && e('SUCCESS,创建模块时模块名包含空格空，提示正确');
r($tester->createModule('模块1'))              && p('status,message') && e('SUCCESS,创建模块成功');
r($tester->createModule('模块1', true))        && p('status,message') && e('SUCCESS,创建模块时模块已存在，提示正确');
r($tester->createModule('模块2'))              && p('status,message') && e('SUCCESS,创建模块成功');
r($tester->createChildModule('子模块 1'))      && p('status,message') && e('SUCCESS,创建子模块时子模块名包含空格，提示正确');
r($tester->createChildModule('子模块1'))       && p('status,message') && e('SUCCESS,创建子模块成功');
r($tester->createChildModule('模块2'))         && p('status,message') && e('SUCCESS,创建子模块成功');
r($tester->createChildModule('子模块1', true)) && p('status,message') && e('SUCCESS,创建子模块时子模块已存在，提示正确');

r($tester->editModule(''))          && p('status,message') && e('SUCCESS,编辑模块时模块为空，提示正确');
r($tester->editModule('模块 2'))    && p('status,message') && e('SUCCESS,编辑模块时模块名包含空格，提示正确');
r($tester->editModule('模块2'))     && p('status,message') && e('SUCCESS,编辑模块时模块已存在，提示正确');
r($tester->editModule('编辑模块1')) && p('status,message') && e('SUCCESS,编辑模块成功');
$tester->closeBrowser();
