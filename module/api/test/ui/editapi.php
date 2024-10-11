#!/usr/bin/env php
<?php

/**

title=编辑文档测试
timeout=0
cid=0

*/
chdir(__DIR__);
include '../lib/editapi.ui.class.php';

$doclib = zenData('doclib');
$doclib->id->range('1-5');
$doclib->type->range('api');
$doclib->product->range('0');
$doclib->name->range('独立接口库1,独立接口库2,独立接口库3,独立接口库4,独立接口库5');
$doclib->acl->range('open');
$doclib->order->range('0');
$doclib->gen(5);

$tester = new createDocTester();
$tester->login();

$editLib = new stdClass();
$editLib->title = '编辑接口库';

$apiDoc = new stdClass();
$apiDoc->docB = 'apiDocB';

$apiPath = new stdClass();
$apiPath->pathB = 'apipathB';

r($tester->editApiLib($editLib, $apiDoc, $apiPath)) && p('message,status') && e('编辑接口文档成功,SUCCESS'); //编辑接口文档成功
