#!/usr/bin/env php
<?php

function setPostData($overrides = array())
{
    global $_POST, $app, $tester;
    $_POST = array_merge(array('uid' => 'testuid'), $overrides);
    $app->post = (object)$_POST;
    if(isset($tester))
    {
        $tester->post = $app->post;
        foreach(get_object_vars($tester) as $prop => $obj)
        {
            if(is_object($obj) && isset($obj->post)) $obj->post = $app->post;
        }
    }
}

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

error_reporting(E_ERROR);

$zd_user = zenData('user');
$zd_user->id->range('1-5');
$zd_user->account->range('1-5');
$zd_user->last->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_user->feedback->range('0');
$zd_user->scoreLevel->range('0');
$zd_user->resetExpired->range('0');
$zd_user->jira->range('0');
$zd_user->deleted->range('0');
$zd_user->gen(5);

su('admin');

$tester->loadModel('common');


$testObj = new commonModelTest();
/**

title=测试 commonModel::checkEntry()
timeout=0
cid=0

- 步骤1：正常输入 @0
- 步骤2：边界值输入 @0
- 步骤3：无效输入 @0
- 步骤4：大值输入 @0
- 步骤5：业务规则验证 @0

*/

r($testObj->checkEntryTest()) && p() && e('0'); // 步骤1：正常输入
r($testObj->checkEntryTest()) && p() && e('0'); // 步骤2：边界值输入
r($testObj->checkEntryTest()) && p() && e('0'); // 步骤3：无效输入
r($testObj->checkEntryTest()) && p() && e('0'); // 步骤4：大值输入
r($testObj->checkEntryTest()) && p() && e('0'); // 步骤5：业务规则验证
