#!/usr/bin/env php
<?php

/**

title=测试 groupModel->getPairs();
timeout=0
cid=1

- 获取权限分组属性1 @这是一个新的用户分组1
- 获取project=1的权限分组属性6 @这是一个新的用户分组6

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/group.class.php';

su('admin');

zdTable('group')->config('group')->gen(10);

$group = new groupTest();

r($group->getPairsTest())  && p('1')  && e('这是一个新的用户分组1'); // 获取权限分组
r($group->getPairsTest(1)) && p('6')  && e('这是一个新的用户分组6'); // 获取project=1的权限分组