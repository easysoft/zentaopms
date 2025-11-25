#!/usr/bin/env php
<?php

/**

title=测试 docModel->copyTemplate();
timeout=0
cid=16150

- 超级管理员可以查看和编辑自己的文档
 - 属性readable @1
 - 属性editable @1
- 超级管理员可以查看和编辑其他用户的公共文档
 - 属性readable @1
 - 属性editable @1
- 作者可以查看和编辑自己的私有文档
 - 属性readable @1
 - 属性editable @1
- 用户不可以查看和编辑别人的私有文档
 - 属性readable @0
 - 属性editable @0
- 用户不能编辑不在编辑白名单列表中的别人的私有文档
 - 属性readable @1
 - 属性editable @0
- 用户可以查看和编辑自己在编辑白名单列表中的别人的私有文档
 - 属性readable @1
 - 属性editable @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

zenData('user')->gen(5);
su('admin');

$doc = new stdclass();
$doc->acl        = 'open';
$doc->addedBy    = 'admin';
$doc->readUsers  = 'admin';
$doc->users      = 'admin';
$doc->groups     = '';
$doc->readGroups = '1,2';

$docTester = new docTest();
r($docTester->setDocPrivTest($doc, 'team')) && p('readable,editable') && e('1,1');   // 超级管理员可以查看和编辑自己的文档

$doc->acl        = 'open';
$doc->addedBy    = 'user1';
$doc->readUsers  = '';
$doc->users      = '';
$doc->groups     = '';
$doc->readGroups = '';

r($docTester->setDocPrivTest($doc, 'team')) && p('readable,editable') && e('1,1');   // 超级管理员可以查看和编辑其他用户的公共文档

su('user1');

$doc->acl        = 'private';
$doc->addedBy    = 'user1';
$doc->readUsers  = '';
$doc->users      = '';
$doc->groups     = '';
$doc->readGroups = '';

r($docTester->setDocPrivTest($doc, 'mine')) && p('readable,editable') && e('1,1');   // 作者可以查看和编辑自己的私有文档

$doc->acl        = 'private';
$doc->addedBy    = 'user2';
$doc->readUsers  = '';
$doc->users      = '';
$doc->groups     = '';
$doc->readGroups = '';

r($docTester->setDocPrivTest($doc, 'team')) && p('readable,editable') && e('0,0');   // 用户不可以查看和编辑别人的私有文档

$doc->addedBy    = 'user2';
$doc->readUsers  = 'user1';
$doc->users      = '';
$doc->groups     = '';
$doc->readGroups = '';

r($docTester->setDocPrivTest($doc, 'team')) && p('readable,editable') && e('1,0');   // 用户不能编辑不在编辑白名单列表中的别人的私有文档

$doc->addedBy    = 'user2';
$doc->readUsers  = '';
$doc->users      = 'user1';
$doc->groups     = '';
$doc->readGroups = '';

r($docTester->setDocPrivTest($doc, 'team')) && p('readable,editable') && e('1,1');   // 用户可以查看和编辑自己在编辑白名单列表中的别人的私有文档