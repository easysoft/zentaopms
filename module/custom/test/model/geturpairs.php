#!/usr/bin/env php
<?php

/**

title=测试 customModel->getURPairs();
timeout=0
cid=15903

- 获取用需概念集合
 - 属性1 @用户需求
 - 属性2 @用户需求
 - 属性3 @用需
 - 属性4 @史诗
 - 属性5 @用户需求

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('lang')->loadYaml('lang')->gen(5);
zenData('user')->gen(5);
su('admin');

$customTester = new customModelTest();
r($customTester->getURPairsTest()) && p('1,2,3,4,5') && e('用户需求,用户需求,用需,史诗,用户需求');  // 获取用需概念集合