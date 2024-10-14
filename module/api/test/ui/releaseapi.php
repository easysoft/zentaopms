#!/usr/bin/env php
<?php

/**

title=发布接口测试
timeout=0
cid=0

- 发布接口成功
 - 测试结果 @发布接口成功
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/releaseapi.ui.class.php';

$doclib = zenData('doclib');
$doclib->id->range('1-2');
$doclib->type->range('api');
$doclib->product->range('0');
$doclib->name->range('独立接口库1,独立接口库2');
$doclib->acl->range('open');
$doclib->order->range('0');
$doclib->gen(2);

$api = zenData('api');
$api->id->range('1-3');
$api->product->range('0');
$api->lib->range('1');
$api->module->range('0');
$api->title->range('apidocA,apidocB,apidocC');
$api->path->range('apipathA,apipathB,apipathC');
$api->protocol->range('HTTP');
$api->method->range('GET');
$api->status->range('done');
$api->version->range('1');
$api->desc->range('');
$api->gen(3);

$apiSpec = zenData('apispec');
$apiSpec->id->range('1-3');
$apiSpec->doc->range('1-3');
$apiSpec->module->range('0');
$apiSpec->title->range('apidocA,apidocB,apidocC');
$apiSpec->path->range('apipathA,apipathB,apipathC');
$apiSpec->protocol->range('HTTP');
$apiSpec->method->range('GET');
$apiSpec->status->range('done');
$apiSpec->version->range('1');
$apiSpec->desc->range('');
$apiSpec->gen(3);

$tester = new createDocTester();
$tester->login();

$apiVersion = new stdClass();
$apiVersion->fstVersion = '版本1';
$apiVersion->secVersion = '版本2';

r($tester->releaseApi($apiVersion)) && p('message,status') && e('发布接口成功,SUCCESS'); //发布接口成功
