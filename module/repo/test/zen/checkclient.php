#!/usr/bin/env php
<?php

/**

title=测试 repoZen::checkClient();
timeout=0
cid=18130

- SCM在notSyncSCM列表中,返回true @1
- checkClient配置为false,返回true @1
- POST中没有client参数,返回false并设置错误属性client @『客户端』不能为空。
- 返回错误信息包含client字段 @1
- clientVersionFile存在,返回true @1
- 返回错误信息包含client字段 @1
- clientVersionFile存在,返回true @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

su('admin');

$repoTest = new repoZenTest();

// 准备临时文件路径用于测试
$tmpFile = $repoTest->objectModel->app->getTmpRoot() . 'test_client_version.log';

// 测试步骤1: SCM在notSyncSCM列表中(如Gitlab),应该返回true,不进行客户端检查
dao::$errors = array();
$postData1 = array('SCM' => 'Gitlab', 'client' => '');
r($repoTest->checkClientTest($postData1, '')) && p() && e('1'); // SCM在notSyncSCM列表中,返回true

// 测试步骤2: checkClient配置为false,应该返回true,不进行客户端检查
dao::$errors = array();
$originalCheckClient = $repoTest->objectModel->config->features->checkClient;
$repoTest->objectModel->config->features->checkClient = false;
$postData2 = array('SCM' => 'Subversion', 'client' => '');
r($repoTest->checkClientTest($postData2, '')) && p() && e('1'); // checkClient配置为false,返回true

// 测试步骤3: POST中没有client参数,应该返回false并设置错误
dao::$errors = array();
$repoTest->objectModel->config->features->checkClient = true; // 明确设置为true
$postData3 = array('SCM' => 'Subversion', 'client' => '');
r($repoTest->checkClientTest($postData3, '')) && p('client') && e('『客户端』不能为空。'); // POST中没有client参数,返回false并设置错误
$repoTest->objectModel->config->features->checkClient = $originalCheckClient; // 恢复原始配置

// 测试步骤4: POST中有client参数但clientVersionFile不存在,应该返回false并设置错误
dao::$errors = array();
$postData4 = array('SCM' => 'Subversion', 'client' => 'svn');
r(is_array($repoTest->checkClientTest($postData4, ''))) && p() && e('1'); // 返回错误信息包含client字段

// 测试步骤5: POST中有client参数且clientVersionFile存在,应该返回true
dao::$errors = array();
touch($tmpFile);
$postData5 = array('SCM' => 'Subversion', 'client' => 'svn');
r($repoTest->checkClientTest($postData5, $tmpFile)) && p() && e('1'); // clientVersionFile存在,返回true
@unlink($tmpFile);

// 测试步骤6: Git类型且client参数为git,但clientVersionFile不存在
dao::$errors = array();
$postData6 = array('SCM' => 'Git', 'client' => 'git');
r(is_array($repoTest->checkClientTest($postData6, ''))) && p() && e('1'); // 返回错误信息包含client字段

// 测试步骤7: Git类型且client参数为git,clientVersionFile存在
dao::$errors = array();
touch($tmpFile);
$postData7 = array('SCM' => 'Git', 'client' => 'git');
r($repoTest->checkClientTest($postData7, $tmpFile)) && p() && e('1'); // clientVersionFile存在,返回true
@unlink($tmpFile);