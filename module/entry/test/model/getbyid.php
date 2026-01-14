#!/usr/bin/env php
<?php

/**

title=测试 entryModel::getById();
timeout=0
cid=16247

- 步骤1：查询存在的entry记录
 - 属性name @应用名称1
 - 属性account @admin
 - 属性code @app1
- 步骤2：查询不存在的entry记录 @0
- 步骤3：查询无效ID（0） @0
- 步骤4：查询负数ID @0
- 步骤5：查询已删除的entry记录
 - 属性name @应用名称9
 - 属性deleted @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$table = zenData('entry');
$table->id->range('1-10');
$table->name->range('应用名称1,应用名称2,应用名称3,应用名称4,应用名称5,应用名称6,应用名称7,应用名称8,应用名称9,应用名称10');
$table->account->range('admin,user1,user2,admin,user1,user2,admin,user1,user2,admin');
$table->code->range('app1,app2,app3,app4,app5,app6,app7,app8,app9,app10');
$table->key->range('key1,key2,key3,key4,key5,key6,key7,key8,key9,key10');
$table->deleted->range('0{8},1{2}');
$table->gen(10);

su('admin');

$entryTest = new entryModelTest();

r($entryTest->getByIdTest(1)) && p('name,account,code') && e('应用名称1,admin,app1'); // 步骤1：查询存在的entry记录
r($entryTest->getByIdTest(999)) && p() && e('0');                                      // 步骤2：查询不存在的entry记录
r($entryTest->getByIdTest(0)) && p() && e('0');                                        // 步骤3：查询无效ID（0）
r($entryTest->getByIdTest(-1)) && p() && e('0');                                       // 步骤4：查询负数ID
r($entryTest->getByIdTest(9)) && p('name,deleted') && e('应用名称9,1');                // 步骤5：查询已删除的entry记录