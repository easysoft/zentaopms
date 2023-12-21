#!/usr/bin/env php
<?php

/**

title=测试 mrModel::getMRProduct();
timeout=0
cid=0

- 不存在的产品 @0
- 代码库不存在 @0
- 代码库产品为空 @0
- 存在的产品
 - 属性name @正常产品1
 - 属性code @code1
 - 属性status @normal

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/mr.class.php';

zdTable('product')->gen(1);
zdTable('repo')->config('repo')->gen(5);
zdTable('mr')->config('mr')->gen(6);

$mrModel = new mrTest();

r($mrModel->getMRProductTester(4)) && p() && e('0'); // 不存在的产品
r($mrModel->getMRProductTester(6)) && p() && e('0'); // 代码库不存在
r($mrModel->getMRProductTester(5)) && p() && e('0'); // 代码库产品为空

r($mrModel->getMRProductTester(1)) && p('name,code,status') && e('正常产品1,code1,normal'); // 存在的产品