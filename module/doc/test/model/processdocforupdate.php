#!/usr/bin/env php
<?php

/**

title=测试 docModel->processDocForUpdate();
cid=1

- 修改产品文档
 - 属性lib @26
 - 属性title @修改产品文档
 - 属性acl @custom
- 修改项目文档
 - 属性lib @18
 - 属性title @修改项目文档
 - 属性acl @custom
- 修改执行文档
 - 属性lib @25
 - 属性title @修改执行文档
 - 属性acl @custom
- 修改自定义文档
 - 属性lib @6
 - 属性title @修改自定义文档
 - 属性acl @custom
- 修改私有文档
 - 属性lib @6
 - 属性title @修改私有文档
 - 属性acl @private
- 修改我的文档
 - 属性lib @11
 - 属性title @修改我的文档
 - 属性acl @private
- 修改自定义文档
 - 属性lib @7
 - 属性title @修改自定义文档
 - 属性acl @private

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

zdTable('doccontent')->gen(50);
zdTable('doc')->config('doc')->gen(50);
zdTable('user')->gen(5);
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

$docTester = new docTest();
r($docTester->processDocForUpdateTest($docIDs[0], $updateProductDoc))   && p('lib,title,acl') && e('26,修改产品文档,custom');   // 修改产品文档
r($docTester->processDocForUpdateTest($docIDs[1], $updateProjectDoc))   && p('lib,title,acl') && e('18,修改项目文档,custom');   // 修改项目文档
r($docTester->processDocForUpdateTest($docIDs[2], $updateExecutionDoc)) && p('lib,title,acl') && e('25,修改执行文档,custom');   // 修改执行文档
r($docTester->processDocForUpdateTest($docIDs[3], $updateCustomDoc))    && p('lib,title,acl') && e('6,修改自定义文档,custom');  // 修改自定义文档
r($docTester->processDocForUpdateTest($docIDs[3], $privateDoc))         && p('lib,title,acl') && e('6,修改私有文档,private');   // 修改私有文档
r($docTester->processDocForUpdateTest($docIDs[4], $mineDoc))            && p('lib,title,acl') && e('11,修改我的文档,private');  // 修改我的文档
r($docTester->processDocForUpdateTest($docIDs[3], $customDoc))          && p('lib,title,acl') && e('7,修改自定义文档,private'); // 修改自定义文档
