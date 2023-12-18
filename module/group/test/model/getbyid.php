#!/usr/bin/env php
<?php

/**

title=测试 groupModel->getByID();
timeout=0
cid=1

- 获取存在的权限分组
 - 属性name @这是一个新的用户分组1
 - 属性role @0
 - 属性desc @这个一个用户分组描述1
- 获取不存在的权限分组
 - 属性name @0
 - 属性role @0
 - 属性desc @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/group.class.php';

su('admin');

zdTable('group')->config('group')->gen(10);

$group = new groupTest();

r($group->getByIDTest(1))  && p('name,role,desc') && e('这是一个新的用户分组1,0,这个一个用户分组描述1'); // 获取存在的权限分组
r($group->getByIDTest(0))  && p('name,role,desc') && e('0,0,0');                                         // 获取不存在的权限分组