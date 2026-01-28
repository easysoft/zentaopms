#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

$query = zenData('userquery');
$query->account->range('admin{5},test{5}');
$query->common->range('0{5},1{1}');
$query->gen(10);

/**

title=测试 searchModel->getQueryList();
timeout=0
cid=18304

- 查询 task 模块的搜索查询列表的第1条记录的名称和用户
 - 第0条的title属性 @这是搜索条件名称6
 - 第0条的account属性 @test
- 查询 task 模块的搜索查询列表的第2条记录的名称和用户
 - 第1条的title属性 @这是搜索条件名称5
 - 第1条的account属性 @admin
- 查询 task 模块的搜索查询列表的第3条记录的名称和用户
 - 第2条的title属性 @这是搜索条件名称4
 - 第2条的account属性 @admin
- 查询 task 模块的搜索查询列表的第4条记录的名称和用户
 - 第3条的title属性 @这是搜索条件名称3
 - 第3条的account属性 @admin
- 查询 task 模块的搜索查询列表的第5条记录的名称和用户
 - 第4条的title属性 @这是搜索条件名称2
 - 第4条的account属性 @admin
- 查询 task 模块的搜索查询列表的第6条记录的名称和用户
 - 第5条的title属性 @这是搜索条件名称1
 - 第5条的account属性 @admin

*/

$search = new searchModelTest();
$module = 'task';

r($search->getQueryListTest($module)) && p('0:title,account') && e('这是搜索条件名称6,test');  //查询 task 模块的搜索查询列表的第1条记录的名称和用户
r($search->getQueryListTest($module)) && p('1:title,account') && e('这是搜索条件名称5,admin'); //查询 task 模块的搜索查询列表的第2条记录的名称和用户
r($search->getQueryListTest($module)) && p('2:title,account') && e('这是搜索条件名称4,admin'); //查询 task 模块的搜索查询列表的第3条记录的名称和用户
r($search->getQueryListTest($module)) && p('3:title,account') && e('这是搜索条件名称3,admin'); //查询 task 模块的搜索查询列表的第4条记录的名称和用户
r($search->getQueryListTest($module)) && p('4:title,account') && e('这是搜索条件名称2,admin'); //查询 task 模块的搜索查询列表的第5条记录的名称和用户
r($search->getQueryListTest($module)) && p('5:title,account') && e('这是搜索条件名称1,admin'); //查询 task 模块的搜索查询列表的第6条记录的名称和用户