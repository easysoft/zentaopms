#!/usr/bin/env php
<?php

/**

title=测试 entryModel::getByKey();
timeout=0
cid=16248

- 执行entryTest模块的getByKeyTest方法，参数是'792b9b972157d2d8531b43e04c0af021' 属性name @应用1
- 执行entryTest模块的getByKeyTest方法，参数是''  @0
- 执行entryTest模块的getByKeyTest方法，参数是'nonexistentkey12345'  @0
- 执行entryTest模块的getByKeyTest方法，参数是'deletedkey'  @0
- 执行entryTest模块的getByKeyTest方法，参数是'specialkey123'  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$table = zenData('entry');
$table->id->range('1-10');
$table->name->range('应用1,应用2,应用3,应用4,应用5,应用6,应用7,应用8,应用9,应用10');
$table->account->range('admin{3},user1{3},user2{4}');
$table->code->range('app1,app2,app3,app4,app5,app6,app7,app8,app9,app10');
$table->key->range('792b9b972157d2d8531b43e04c0af021,abcd1234567890123456789012345678,testkey001,testkey002,testkey003,deletedkey,key007,key008,key009,key010');
$table->deleted->range('0{5},1{5}');
$table->gen(10);

su('admin');

$entryTest = new entryModelTest();

r($entryTest->getByKeyTest('792b9b972157d2d8531b43e04c0af021')) && p('name') && e('应用1');
r($entryTest->getByKeyTest('')) && p() && e('0');
r($entryTest->getByKeyTest('nonexistentkey12345')) && p() && e('0');
r($entryTest->getByKeyTest('deletedkey')) && p() && e('0');
r($entryTest->getByKeyTest('specialkey123')) && p() && e('0');