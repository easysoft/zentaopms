#!/usr/bin/env php
<?php
/**
title=用户登录
cid=10001
pid=79
>>  登录测试 : PASS!
**/

include dirname(__FILE__, 3) . '/lib/ui.php';
include dirname(__FILE__) . '/createproduct.class.php';

zdTable('product')->config('product', false, 1)->gen(10);
$createTester = new createProductTester();

r($createTester->createDefault(''))                    && p('message:nameTip')   && e('『产品名称』不能为空。'); // 缺少产品名称，创建失败
r($createTester->createDefault('默认产品' . time()))   && p('status')            && e('SUCCESS'); // 使用默认选项创建产品
r($createTester->checkLocatePage('正常产品' . time())) && p('url:module,method') && e('product,browse'); // 创建正常产品成功
