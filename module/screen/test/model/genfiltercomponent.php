#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 screenModel->genFilterComponent();
timeout=0
cid=18229

- 生成项目筛选器
 - 第0条的value属性 @11
 - 第0条的label属性 @项目11
 - 第1条的value属性 @12
 - 第1条的label属性 @项目12
 - 第2条的value属性 @13
 - 第2条的label属性 @项目13
- 生成产品筛选器
 - 第0条的value属性 @1
 - 第0条的label属性 @正常产品1
 - 第1条的value属性 @2
 - 第1条的label属性 @正常产品2
 - 第2条的value属性 @3
 - 第2条的label属性 @正常产品3
- 生成用户筛选器
 - 第0条的value属性 @admin
 - 第0条的label属性 @admin
 - 第1条的value属性 @user1
 - 第1条的label属性 @用户1
 - 第2条的value属性 @user2
 - 第2条的label属性 @用户2
- 生成部门筛选器
 - 第0条的value属性 @0
 - 第0条的label属性 @/
 - 第1条的value属性 @1
 - 第1条的label属性 @/产品部1
 - 第2条的value属性 @2
 - 第2条的label属性 @/开发部2
- 生成filter为system筛选器 @0

*/

zenData('project')->loadYaml('project')->gen(5);
zenData('product')->gen(5);
zenData('user')->gen(5);
zenData('dept')->gen(5);
zenData('action')->gen(5);

$screenTest = new screenModelTest();

$filterTypeList = array('project', 'product', 'user', 'dept', 'system');

r($screenTest->genFilterComponentTest($filterTypeList[0])) && p('0:value,label;1:value,label;2:value,label') && e('11,项目11;12,项目12;13,项目13');       //生成项目筛选器
r($screenTest->genFilterComponentTest($filterTypeList[1])) && p('0:value,label;1:value,label;2:value,label') && e('1,正常产品1;2,正常产品2;3,正常产品3'); //生成产品筛选器
r($screenTest->genFilterComponentTest($filterTypeList[2])) && p('0:value,label;1:value,label;2:value,label') && e('admin,admin;user1,用户1;user2,用户2'); //生成用户筛选器
r($screenTest->genFilterComponentTest($filterTypeList[3])) && p('0:value,label;1:value,label;2:value,label') && e('0,/;1,/产品部1;2,/开发部2');             //生成部门筛选器
r($screenTest->genFilterComponentTest($filterTypeList[4])) && p('') && e('0'); //生成filter为system筛选器