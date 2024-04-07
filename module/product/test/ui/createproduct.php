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

include dirname(__FILE__, 5) . '/test/lib/ui.php';
include dirname(__FILE__, 2) . '/lib/createproduct.ui.class.php';

zdTable('product')->config('product', false, 2)->gen(10);
$createTester = new createProductTester();

$nameList = array();
$nameList['null']    = '';
$nameList['default'] = '默认产品';
$nameList['normal']  = '正常产品';
$nameList['branch']  = '多分支产品产品';

r($createTester->createDefault($nameList['null']))       && p('message:nameTip')   && e('『产品名称』不能为空。');                     // 缺少产品名称，创建失败
r($createTester->createDefault($nameList['default']))    && p('status')            && e('SUCCESS');                                    // 使用默认选项创建产品
r($createTester->createDefault($nameList['default']))    && p('message:nameTip')   && e('『产品名称』已经有『默认产品』这条记录了。'); // 创建重复名称的产品
r($createTester->checkLocatePage($nameList['normal']))   && p('module,method')     && e('product,browse');                             // 创建正常产品后的跳转链接检查
r($createTester->createMultiBranch($nameList['branch'])) && p('status')            && e('SUCCESS');                                    // 创建正常产品成功

$createTester->closeBrowser();
