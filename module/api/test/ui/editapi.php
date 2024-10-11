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
