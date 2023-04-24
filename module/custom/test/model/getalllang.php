#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/custom.class.php';
su('admin');

$ztLang = zdTable('lang');
$ztLang->id->range('1-3');
$ztLang->lang->range('zh-cn');
$ztLang->module->range('common,myMenu,my');
$ztLang->section->range('``,menu,featureBar-todo');
$ztLang->key->range('productCommon,index,all');
$ztLang->value->range('1-3')->prefix('测试');
$ztLang->gen(3);

/**

title=测试 customModel->getAllLang();
cid=1
pid=1

测试正常查询 >> 3

*/

$custom = new customTest();

r($custom->getAllLangTest()) && p() && e('3');  //测试正常查询
