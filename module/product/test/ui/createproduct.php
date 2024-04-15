#!/usr/bin/env php
<?php
/**
title=用户登录
timeout=0
cid=10001

『产品名称』不能为空。
SUCCESS
『产品名称』已经有『正常产品1710742180』这条记录了。
product
browse
SUCCESS
*/
chdir(__DIR__);
include '../lib/createproduct.ui.class.php';

//zdTable('product')->config('product', false, 2)->gen(10);
//zendata::loadYaml('product', false, 2)->createFor('product', 10);
$tester = new createProductTester();
$tester->login();

$products = array();
$products['null']    = '';
$products['default'] = '默认产品';
$products['normal']  = '正常产品';
$products['branch']  = '多分支产品产品';

r($tester->createDefault($products['null']))       && p('message')       && e('表单提示信息正确'); // 缺少产品名称，创建失败
r($tester->createDefault($products['default']))    && p('status')        && e('SUCCESS');          // 使用默认选项创建产品
r($tester->createDefault($products['default']))    && p('message')       && e('表单提示信息正确'); // 创建重复名称的产品
r($tester->checkLocating($products['normal']))     && p('module,method') && e('product,browse');   // 创建正常产品后的跳转链接检查
r($tester->createMultiBranch($products['branch'])) && p('status')        && e('SUCCESS');          // 创建正常产品成功

$tester->closeBrowser();
