#!/usr/bin/env php
<?php

/**
title=测试 reporeviewflowModel::getByID();
timeout=0
cid=0

- 测试ID1的数据
 - 属性id @1
 - 属性name @review_flow1
 - 属性status @enable
 - 属性branchType @0
- 测试无效ID @0
*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 使用现有的repo数据
zenData('repo')->gen(10);
zenData('ops_review_flow')->gen(10);

// 用户登录
su('admin');

// 创建测试实例
$flowTest = new reporeviewflowTest();
r($flowTest->getByID(1)) && p('id,name,status,branchType') && e('1,review_flow1,enable,0'); // 测试ID1的数据
r($flowTest->getByID(0)) && p()                            && e(0);                         // 测试无效ID
