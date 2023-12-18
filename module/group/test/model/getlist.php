#!/usr/bin/env php
<?php

/**

title=测试 groupModel->getList();
timeout=0
cid=1

- 获取权限分组列表
 - 第0条的name属性 @这是一个新的用户分组1
 - 第0条的role属性 @0
 - 第0条的desc属性 @这个一个用户分组描述1
- 获取project=1的权限分组列表
 - 第0条的name属性 @这是一个新的用户分组6
 - 第0条的role属性 @0
 - 第0条的desc属性 @这个一个用户分组描述6

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/group.class.php';

su('admin');

zdTable('group')->config('group')->gen(10);

$group = new groupTest();

r($group->getListTest())  && p('0:name,role,desc') && e('这是一个新的用户分组1,0,这个一个用户分组描述1'); // 获取权限分组列表
r($group->getListTest(1)) && p('0:name,role,desc') && e('这是一个新的用户分组6,0,这个一个用户分组描述6'); // 获取project=1的权限分组列表