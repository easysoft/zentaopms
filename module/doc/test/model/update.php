#!/usr/bin/env php
<?php
/**

title=测试 docModel->update();
cid=1
outtime=0

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
$noTitle            = array('lib' => $libIDs[0], 'title' => '',               'acl' => $acl[0]);

$docTester = new docTest();
r($docTester->updateTest($docIDs[0], $updateProductDoc))   && p('0:field,old,new') && e('title,文档标题41,修改产品文档');// 修改产品文档
r($docTester->updateTest($docIDs[1], $updateProjectDoc))   && p('0:field,old,new') && e('title,文档标题31,修改项目文档');                 // 修改项目文档
r($docTester->updateTest($docIDs[2], $updateExecutionDoc)) && p('0:field,old,new') && e('module,3,0');// 修改执行文档
r($docTester->updateTest($docIDs[3], $updateCustomDoc))    && p('0:field,old,new') && e('title,文档标题11,修改自定义文档');             // 修改自定义文档
r($docTester->updateTest($docIDs[3], $privateDoc))         && p('2:field,old,new') && e('acl,custom,private');              // 修改私有文档
r($docTester->updateTest($docIDs[4], $mineDoc))            && p('0:field,old,new') && e('title,文档标题1,修改我的文档');              // 修改我的文档
r($docTester->updateTest($docIDs[3], $customDoc))          && p('0:field,old,new') && e('lib,6,7');                // 修改自定义文档
r($docTester->updateTest($docIDs[1], $noTitle))            && p('title:0')         && e('『文档标题』不能为空。');        // 修改标题为空
