#!/usr/bin/env php
<?php

/**

title=测试 groupModel->getUserPairs();
timeout=0
cid=1

- 判断分组是否包含正确的account属性user1 @用户1
- 判断分组是否不包含错误的account属性user2 @` `
- 判断不存在的分组是否返回空数组 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/group.class.php';

su('admin');

zdTable('user')->gen(100);
zdTable('group')->gen(5);
zdTable('usergroup')->config('usergroup')->gen(10);

$group = new groupTest();

r($group->getUserPairsTest(1)) && p('user1')  && e('用户1'); // 判断分组是否包含正确的account
r($group->getUserPairsTest(1)) && p('user2')  && e('` `');   // 判断分组是否不包含错误的account
r($group->getUserPairsTest(6)) && p()         && e('0');     // 判断不存在的分组是否返回空数组