#!/usr/bin/env php
<?php

/**

title=测试 groupModel->updateUser();
timeout=0
cid=16727

- 更新分组成员，检查已有用户属性user1 @用户1
- 更新分组成员，检查已删除用户属性user6 @~~
- 更新分组成员，检查新增用户属性user10 @用户10
- 更新分组成员，检查已有用户属性user1 @用户1
- 更新分组成员，检查新增用户属性user10 @用户10

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

zenData('user')->gen(100);
zenData('group')->gen(5);
zenData('usergroup')->loadYaml('usergroup')->gen(10);

$group = new groupModelTest();

$_POST['members'] = array('user1' => 'user1', 'user10' => 'user10');

r($group->updateUserTest(1)) && p('user1')  && e('用户1');  // 更新分组成员，检查已有用户
r($group->updateUserTest(1)) && p('user6')  && e('~~');     // 更新分组成员，检查已删除用户
r($group->updateUserTest(1)) && p('user10') && e('用户10'); // 更新分组成员，检查新增用户
r($group->updateUserTest(2)) && p('user1')  && e('用户1');  // 更新分组成员，检查已有用户
r($group->updateUserTest(2)) && p('user10') && e('用户10'); // 更新分组成员，检查新增用户