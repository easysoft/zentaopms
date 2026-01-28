#!/usr/bin/env php
<?php

/**

title=测试 docModel->update();
cid=16156

- 修改产品文档
 - 第0条的field属性 @title
 - 第0条的old属性 @文档标题41
 - 第0条的new属性 @修改产品文档
- 修改项目文档
 - 第0条的field属性 @title
 - 第0条的old属性 @文档标题31
 - 第0条的new属性 @修改项目文档
- 修改执行文档
 - 第0条的field属性 @module
 - 第0条的old属性 @3
 - 第0条的new属性 @0
- 修改自定义文档
 - 第0条的field属性 @title
 - 第0条的old属性 @文档标题11
 - 第0条的new属性 @修改自定义文档
- 修改私有文档
 - 第0条的field属性 @title
 - 第0条的old属性 @修改自定义文档
 - 第0条的new属性 @修改私有文档
- 修改我的文档
 - 第0条的field属性 @title
 - 第0条的old属性 @文档标题1
 - 第0条的new属性 @修改我的文档
- 修改自定义文档
 - 第0条的field属性 @lib
 - 第0条的old属性 @6
 - 第0条的new属性 @7
- 修改标题为空第title条的0属性 @『文档标题』不能为空。

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('doccontent')->gen(50);
zenData('doc')->loadYaml('doc')->gen(50);
zenData('user')->gen(5);
su('admin');

$docIDs = array(41, 31, 39, 11, 1);
$acl    = array('open', 'custom', 'private');
$libIDs = array(26, 18, 25, 6, 11, 7);
$groups = '1,2,3';
$users  = 'admin,dev1,dev10';

$updateProductDoc   = array('lib' => $libIDs[0], 'title' => '修改产品文档',   'acl' => $acl[1]);
$updateProjectDoc   = array('lib' => $libIDs[1], 'title' => '修改项目文档',   'acl' => $acl[1]);
$updateExecutionDoc = array('lib' => $libIDs[2], 'title' => '修改执行文档',   'acl' => $acl[1]);
$updateCustomDoc    = array('lib' => $libIDs[3], 'title' => '修改自定义文档', 'acl' => $acl[1]);
$privateDoc         = array('lib' => $libIDs[3], 'title' => '修改私有文档',   'acl' => $acl[2]);
$mineDoc            = array('lib' => $libIDs[4], 'title' => '修改我的文档',   'acl' => $acl[2]);
$customDoc          = array('lib' => $libIDs[5], 'title' => '修改自定义文档', 'acl' => $acl[2], 'groups' => $groups, 'users' => $users);
$noTitle            = array('lib' => $libIDs[0], 'title' => '',               'acl' => $acl[0]);

$docTester = new docModelTest();
r($docTester->updateTest($docIDs[0], $updateProductDoc))   && p('0:field,old,new') && e('title,文档标题41,修改产品文档');   // 修改产品文档
r($docTester->updateTest($docIDs[1], $updateProjectDoc))   && p('0:field,old,new') && e('title,文档标题31,修改项目文档');   // 修改项目文档
r($docTester->updateTest($docIDs[2], $updateExecutionDoc)) && p('0:field,old,new') && e('module,3,0');                      // 修改执行文档
r($docTester->updateTest($docIDs[3], $updateCustomDoc))    && p('0:field,old,new') && e('title,文档标题11,修改自定义文档'); // 修改自定义文档
r($docTester->updateTest($docIDs[3], $privateDoc))         && p('0:field,old,new') && e('title,修改自定义文档,修改私有文档');   // 修改私有文档
r($docTester->updateTest($docIDs[4], $mineDoc))            && p('0:field,old,new') && e('title,文档标题1,修改我的文档');    // 修改我的文档
r($docTester->updateTest($docIDs[3], $customDoc))          && p('0:field,old,new') && e('lib,6,7');                         // 修改自定义文档
r($docTester->updateTest($docIDs[1], $noTitle))            && p('title:0')         && e('『文档标题』不能为空。');          // 修改标题为空
