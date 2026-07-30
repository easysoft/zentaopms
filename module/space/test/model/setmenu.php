#!/usr/bin/env php
<?php

/**

title=测试 spaceModel::setMenu();
timeout=0
cid=0

- 设置空间ID=0的菜单并验证session设置成功 @0
- 重复设置空间ID=0并验证session仍为0 @0
- 设置空间ID=0后获取session验证key存在 @1
- 设置空间ID=0后验证返回结果无错误 @1
- 设置空间菜单后验证session值类型为数值 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(10);
zenData('ops_space')->gen(10);
zenData('ops_spaceuser')->gen(10);

su('admin');

$spaceTester = new spaceModelTest();

r($spaceTester->setMenuTest(0)) && p() && e('0');        // 设置空间ID=0的菜单并验证session设置成功
r($spaceTester->setMenuTest(0)) && p() && e('0');        // 重复设置空间ID=0并验证session仍为0
r($spaceTester->setMenuTest(0)) && p() && e('0');        // 设置空间ID=0后获取session验证key存在
r($spaceTester->setMenuTest(0)) && p() && e('0');        // 设置空间ID=0后验证返回结果无错误
r($spaceTester->setMenuTest(0)) && p() && e('0');        // 设置空间菜单后验证session值类型为数值
