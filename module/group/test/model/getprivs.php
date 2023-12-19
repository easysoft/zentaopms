#!/usr/bin/env php
<?php

/**

title=测试 groupModel->getPrivs();
timeout=0
cid=1

- 测试是否存在module1-method1权限属性module1 @method1
- 测试是否存在module6-method6权限属性module6 @method6
- 测试不存在module2权限属性module2 @` `
- 测试不存在的分组是否有权限 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/group.class.php';

su('admin');

zdTable('group')->gen(5);
zdTable('grouppriv')->config('grouppriv')->gen(10);

$group = new groupTest();
$groupID = 1;

r($group->getPrivsTest($groupID)) && p('module1') && e('method1'); //测试是否存在module1-method1权限
r($group->getPrivsTest($groupID)) && p('module6') && e('method6'); //测试是否存在module6-method6权限
r($group->getPrivsTest($groupID)) && p('module2')         && e('` `');     //测试不存在module2权限
r($group->getPrivsTest(6))        && p()                  && e('0');       //测试不存在的分组是否有权限
