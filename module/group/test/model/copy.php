#!/usr/bin/env php
<?php

/**

title=测试 groupModel->copy();
timeout=0
cid=1

- 正常复制属性name @复制的分组1
- 分组名称已存在第name条的0属性 @『分组名称』已经有『这是一个新的用户分组5』这条记录了，请调整后再试。
- 只复制权限
 - 属性name @复制的分组2
 - 属性privs @module2-method2|module7-method7
 - 属性users @~~
- 只复制用户
 - 属性name @复制的分组3
 - 属性privs @~~
 - 属性users @user2|user7
- 复制权限和用户
 - 属性name @复制的分组4
 - 属性privs @module2-method2|module7-method7
 - 属性users @user2|user7

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/group.class.php';

su('admin');

zdTable('user')->gen(100);
zdTable('group')->gen(5);
zdTable('grouppriv')->config('grouppriv')->gen(10);
zdTable('usergroup')->config('usergroup')->gen(10);

$groupID = 2;

$normal1Group = array('name' => '复制的分组1');
$normal2Group = array('name' => '复制的分组2');
$normal3Group = array('name' => '复制的分组3');
$normal4Group = array('name' => '复制的分组4');
$existGroup   = array('name' => '这是一个新的用户分组5');

$group = new groupTest();
r($group->copyTest($groupID, $normal1Group)) && p('name')   && e('复制的分组1');                                                            // 正常复制
r($group->copyTest($groupID, $existGroup))   && p('name:0') && e('『分组名称』已经有『这是一个新的用户分组5』这条记录了，请调整后再试。');  // 分组名称已存在

r($group->copyTest($groupID, $normal2Group, array('copyPriv')))             && p('name,privs,users') && e('复制的分组2,module2-method2|module7-method7,~~');           // 只复制权限
r($group->copyTest($groupID, $normal3Group, array('copyUser')))             && p('name,privs,users') && e('复制的分组3,~~,user2|user7');                               // 只复制用户
r($group->copyTest($groupID, $normal4Group, array('copyPriv', 'copyUser'))) && p('name,privs,users') && e('复制的分组4,module2-method2|module7-method7,user2|user7');  // 复制权限和用户
