#!/usr/bin/env php
<?php

/**

title=创建产品测试
timeout=0
cid=70

- 缺少产品名称，创建失败
 -  测试结果 @创建产品表单页提示信息正确
 -  最终测试状态 @SUCCESS
- 使用默认选项创建产品 最终测试状态 @SUCCESS
- 创建重复名称的产品 测试结果 @创建产品表单页提示信息正确
- 创建正常产品后的跳转链接检查
 - 属性module @product
 - 属性method @browse
- 创建正常产品成功 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/createproduct.ui.class.php';

zendata('product')->loadYaml('product', false, 2)->gen(10);
$tester = new createProductTester();
$tester->login();

$products = array();
$products['null']    = '';
$products['default'] = '默认产品';
$products['normal']  = '正常产品';
$products['branch']  = '多分支产品产品';

r($tester->createDefault($products['null']))       && p('message,status') && e('创建产品表单页提示信息正确,SUCCESS'); // 缺少产品名称，创建失败
r($tester->createDefault($products['default']))    && p('status')         && e('SUCCESS');                            // 使用默认选项创建产品
r($tester->createDefault($products['default']))    && p('message')        && e('创建产品表单页提示信息正确');         // 创建重复名称的产品
r($tester->checkLocating($products['normal']))     && p('module,method')  && e('product,browse');                     // 创建正常产品后的跳转链接检查
r($tester->createMultiBranch($products['branch'])) && p('status')         && e('SUCCESS');                            // 创建正常产品成功

$tester->closeBrowser();
