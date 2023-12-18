#!/usr/bin/env php
<?php

/**

title=测试 groupModel->copyUser();
timeout=0
cid=1

- 验证group1的成员属性1 @user1|user6
- 验证group2的成员属性2 @user2|user7
- 验证不存在的group成员属性6 @` `

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/group.class.php';

su('admin');

zdTable('user')->gen(100);
zdTable('group')->gen(5);
zdTable('usergroup')->config('usergroup')->gen(10);

$group = new groupTest();

r($group->getAllGroupMembersTest()) && p('1') && e('user1|user6'); // 验证group1的成员
r($group->getAllGroupMembersTest()) && p('2') && e('user2|user7'); // 验证group2的成员
r($group->getAllGroupMembersTest()) && p('6') && e('` `');         // 验证不存在的group成员