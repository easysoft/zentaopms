#!/usr/bin/env php
<?php

/**

title=测试 biModel::getColumns();
timeout=0
cid=0

- 步骤1：MySQL驱动获取产品表字段类型
 - 属性id @INT24
 - 属性name @VAR_STRING
- 步骤2：无效驱动参数测试 @0
- 步骤3：返回原始列信息测试 @returnOrigin
- 步骤4：获取多个字段的类型信息
 - 属性id @INT24
 - 属性name @VAR_STRING
 - 属性code @VAR_STRING
 - 属性type @VAR_STRING
- 步骤5：获取不同表的字段类型
 - 属性id @INT24
 - 属性title @VAR_STRING
- 步骤6：DM驱动兼容性测试 @0
- 步骤7：复杂SQL查询字段类型获取
 - 属性id @INT24
 - 属性name @VAR_STRING
 - 属性title @VAR_STRING

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

// 准备测试数据
zenData('product')->gen(10);
zenData('bug')->gen(5);

// 设置用户权限
su('admin');

// 创建测试实例
$biTest = new biTest();

r($biTest->getColumnsTest('select id, name from zt_product', 'mysql', false)) && p('id,name') && e('INT24,VAR_STRING'); // 步骤1：MySQL驱动获取产品表字段类型
r($biTest->getColumnsTest('select * from zt_product', 'invaliddriver', false)) && p() && e('0'); // 步骤2：无效驱动参数测试
r($biTest->getColumnsTest('select id, name from zt_product', 'mysql', true)) && p() && e('returnOrigin'); // 步骤3：返回原始列信息测试
r($biTest->getColumnsTest('select id, name, code, type from zt_product', 'mysql', false)) && p('id,name,code,type') && e('INT24,VAR_STRING,VAR_STRING,VAR_STRING'); // 步骤4：获取多个字段的类型信息
r($biTest->getColumnsTest('select id, title from zt_bug', 'mysql', false)) && p('id,title') && e('INT24,VAR_STRING'); // 步骤5：获取不同表的字段类型
r($biTest->getColumnsTest('select * from zt_product', 'dm', false)) && p() && e('0'); // 步骤6：DM驱动兼容性测试
r($biTest->getColumnsTest('select p.id, p.name, b.title from zt_product p left join zt_bug b on p.id = b.product limit 1', 'mysql', false)) && p('id,name,title') && e('INT24,VAR_STRING,VAR_STRING'); // 步骤7：复杂SQL查询字段类型获取