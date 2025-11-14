#!/usr/bin/env php
<?php

/**

title=测试 groupModel->getPairs();
timeout=0
cid=16707

- 获取权限分组属性1 @这是一个新的用户分组1
- 获取权限分组属性3 @这是一个新的用户分组3
- 获取project=2的权限分组属性7 @这是一个新的用户分组7
- 获取project=1的权限分组属性6 @这是一个新的用户分组6
- 获取project=1的权限分组属性10 @这是一个新的用户分组10

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/group.unittest.class.php';

su('admin');

zenData('group')->loadYaml('group')->gen(10);

global $app;
$app->user->feedback = 1;

$group = new groupTest();
r($group->getPairsTest())   && p('1')  && e('这是一个新的用户分组1'); // 获取权限分组
r($group->getPairsTest())   && p('3')  && e('这是一个新的用户分组3'); // 获取权限分组
r($group->getPairsTest(2))  && p('7')  && e('这是一个新的用户分组7'); // 获取project=2的权限分组

$app->user->feedback = 0;
r($group->getPairsTest(1)) && p('6')   && e('这是一个新的用户分组6');  // 获取project=1的权限分组
r($group->getPairsTest(5)) && p('10')  && e('这是一个新的用户分组10'); // 获取project=1的权限分组
