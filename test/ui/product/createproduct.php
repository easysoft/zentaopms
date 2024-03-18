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

include dirname(__FILE__, 3) . '/lib/ui.php';
include dirname(__FILE__) . '/createproduct.class.php';

zdTable('product')->config('product', false, 1)->gen(10);
$createTester = new createProductTester();

$name = '正常产品' . time();

r($createTester->createDefault(''))                            && p('message:nameTip')   && e('『产品名称』不能为空。'); // 缺少产品名称，创建失败
r($createTester->createDefault($name))                         && p('status')            && e('SUCCESS');                // 使用默认选项创建产品
r($createTester->createDefault($name))                         && p('message:nameTip')   && e('SUCCESS');                // 使用默认选项创建产品
r($createTester->checkLocatePage('正常产品' . time()))         && p('url:module,method') && e('product,browse');         // 创建正常产品后的跳转链接检查
r($createTester->createMultiBranch('多分支产品产品' . time())) && p('status')            && e('SUCCESS');                // 创建正常产品成功

closeBrowser();
