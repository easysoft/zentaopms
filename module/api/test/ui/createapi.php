#!/usr/bin/env php
<?php

/**

title=创建文档测试
timeout=0
cid=0

*/
chdir(__DIR__);
include '../lib/createapi.ui.class.php';

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

$apiLib = new stdClass();
$apiLib->name = '接口库A';

$apiDoc = new stdClass();
$apiDoc->docA = 'apiDocA';

$apiPath = new stdClass();
$apiPath->pathA = 'apipathA';

r($tester->createApiDoc($apiLib, $apiDoc, $apiPath)) && p('message,status') && e('创建接口文档成功,SUCCESS'); //创建接口文档成功
