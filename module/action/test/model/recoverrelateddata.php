#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

zenData('action')->gen(10);
zenData('product')->gen(5);
zenData('project')->gen(10);
zenData('doclib')->gen(2);
zenData('task')->gen(2);
zenData('release')->gen(2);
zenData('build')->gen(2);

su('admin');

/**

title=测试 actionModel->recoverRelatedData();
timeout=0
cid=14927

- build表的deleted应为0 @0
- product表的deleted应为0 @0
- execution表的deleted应为0 @0
- file表的deleted应为0 @0
- doclib表的deleted应为0 @0

*/

$actionTest = new actionTest();

global $tester;
$actionModel = $tester->loadModel('action');

$action = (object)['objectType' => 'release', 'objectID' => 1];
$object = (object)['shadow' => 1];
$actionModel->recoverRelatedData($action, $object);
r($tester->dao->select('deleted')->from(TABLE_BUILD)->where('id')->eq(1)->fetch('deleted')) && p('') && e('0'); // build表的deleted应为0

$action = (object)['objectType' => 'project', 'objectID' => 2];
$object = (object)['id' => 2, 'hasProduct' => false, 'name' => '项目2', 'acl' => 'private', 'multiple' => ''];
$actionModel->recoverRelatedData($action, $object);
r($tester->dao->select('deleted')->from(TABLE_PRODUCT)->where('id')->eq(1)->fetch('deleted')) && p('') && e('0'); // product表的deleted应为0

$action = (object)['objectType' => 'project', 'objectID' => 2];
$object = (object)['id' => 2, 'hasProduct' => true, 'name' => '项目2', 'acl' => 'private', 'multiple' => ''];
$actionModel->recoverRelatedData($action, $object);
r($tester->dao->select('deleted')->from(TABLE_EXECUTION)->where('project')->eq(2)->andWhere('multiple')->eq('0')->fetch('deleted')) && p('') && e('0'); // execution表的deleted应为0

$action = (object)['objectType' => 'doc', 'objectID' => 1];
$object = (object)['files' => [1,2], 'id' => 1];
$actionModel->recoverRelatedData($action, $object);
r($tester->dao->select('deleted')->from(TABLE_FILE)->where('id')->eq(1)->fetch('deleted'))   && p('') && e('0'); // file表的deleted应为0
r($tester->dao->select('deleted')->from(TABLE_DOCLIB)->where('id')->eq(1)->fetch('deleted')) && p('') && e('0'); // doclib表的deleted应为0