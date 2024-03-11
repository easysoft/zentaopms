#!/usr/bin/env php
<?php
/**
title=用户登录
cid=10001
pid=79
>>  登录测试 : PASS!
**/

include dirname(__FILE__, 4) . '/ui/lib/ui.php';
include dirname(__FILE__) . '/product.class.php';

$tester = new product();

$product = array();
r($tester->createProduct($product)) && p('nameTip:text') && e('『产品名称』不能为空。'); // 判断名称是否必输
die;
$product['name'] = array('setValue' => '产品' . time());
$result = $tester->createProductTest($product);
r($result->settings->getText()) && p() && e('设置');                  // 只输入名称创建产品

$result = $tester->createProductTest($product);
r($result->nameTip->getText()) && p() && e('『产品名称』已经有'); // 相同名称判断

$product['name']     = array('setValue' => '产品' . time());
$product['type']     = array('picker' => '多分支');
$product['reviewer'] = array('multiPicker' => array('xmjl01'));
$result = $tester->createProductTest($product);
r($result->branchdropmenu->getText()) && p() && e('所有');            // 创建多分支产品

$tester->closeBrowser();
