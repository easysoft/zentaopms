#!/usr/bin/env php
<?php

use function zin\wg;

include dirname(__FILE__, 5) . '/test/lib/init.php';
zenData('user')->gen(60);
su('admin');

/**

title=测试 actionModel->buildActionList();
timeout=0
cid=14877

- 传入空数组，检查结果。 @0
- 传入 actions，检查第一条记录的content字段。 @1
- 传入 actions，检查第一条记录。
 - 属性id @1
 - 属性action @opened
 - 属性hasRendered @1
- 传入 actions，检查第二条记录。
 - 属性id @2
 - 属性action @edited
 - 属性hasRendered @1
 - 属性comment @test
 - 属性commentEditable @1
- 切换 user1 账号， 传入 actions，检查第二条记录。
 - 属性id @2
 - 属性action @edited
 - 属性hasRendered @1
 - 属性comment @test
 - 属性commentEditable @0
- 将 commentEditable 参数为 false， 传入 actions，检查第二条记录。
 - 属性id @2
 - 属性action @edited
 - 属性hasRendered @1
 - 属性comment @test
 - 属性commentEditable @0

*/

$actions[1] = new stdclass();
$actions[1]->id         = 1;
$actions[1]->objectType = 'task';
$actions[1]->objectID   = 1;
$actions[1]->action     = 'opened';
$actions[1]->actor      = 'user1';
$actions[1]->date       = date('Y-m-d H:i:s');
$actions[1]->comment    = '';
$actions[1]->extra      = '';

$actions[2] = new stdclass();
$actions[2]->id         = 2;
$actions[2]->objectType = 'task';
$actions[2]->objectID   = 1;
$actions[2]->action     = 'edited';
$actions[2]->actor      = 'admin';
$actions[2]->date       = date('Y-m-d H:i:s');
$actions[2]->comment    = 'test';
$actions[2]->extra      = 'test';

global $tester;
$actionModel = $tester->loadModel('action');
$list1 = $actionModel->buildActionList(array());
$list2 = $actionModel->buildActionList($actions);

su('user1');
$list3 = $actionModel->buildActionList($actions);
$list4 = $actionModel->buildActionList($actions, array(), false);

$list2[1]->commentEditable = (int)$list2[1]->commentEditable;
$list3[1]->commentEditable = (int)$list3[1]->commentEditable;
$list4[1]->commentEditable = (int)$list4[1]->commentEditable;

r(count($list1))                  && p() && e('0'); // 传入空数组，检查结果。
r((int)isset($list2[0]->content)) && p() && e('1'); // 传入 actions，检查第一条记录的content字段。

r((array)$list2[0]) && p('id,action,hasRendered')                         && e('1,opened,1');        // 传入 actions，检查第一条记录。
r((array)$list2[1]) && p('id,action,hasRendered,comment,commentEditable') && e('2,edited,1,test,1'); // 传入 actions，检查第二条记录。
r((array)$list3[1]) && p('id,action,hasRendered,comment,commentEditable') && e('2,edited,1,test,0'); // 切换 user1 账号， 传入 actions，检查第二条记录。
r((array)$list4[1]) && p('id,action,hasRendered,comment,commentEditable') && e('2,edited,1,test,0'); // 将 commentEditable 参数为 false， 传入 actions，检查第二条记录。