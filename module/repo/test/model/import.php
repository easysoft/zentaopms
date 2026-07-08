#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/* 准备 provider 数据：3 条 Subversion 类型，url 均为不可解析格式，确保不触发外部 http */
ob_start();
zenData('ops_provider')->loadYaml('ops_provider', false, 2)->gen(3);
ob_end_clean();

su('admin');

/**

title=测试 repoModel::import();
timeout=0
cid=0

- 缺失 providerID 字段直接返回 false @false
- providerID=0 走 empty 短路返回 false @false
- providerID 不存在 fetchByID 返回空返回 false @false
- Subversion 协议 url 为 '://' parse_url 失败返回 false @false
- Subversion 协议 url 为 'abc' parse_url 失败返回 false @false

*/

$tester = new repoModelTest();

$baseForm = array('acl' => 'open', 'name' => 'unittest', 'desc' => '', 'product' => '1', 'space' => 1, 'mirror' => 'readonly', 'repo' => '', 'organize' => '', 'password' => '', 'account' => '');

r($tester->importTest($baseForm))                                                      && p() && e('false'); // 步骤1：无 providerID 字段
r($tester->importTest(array_merge($baseForm, array('providerID' => 0))))               && p() && e('false'); // 步骤2：providerID=0
r($tester->importTest(array_merge($baseForm, array('providerID' => 99999))))           && p() && e('false'); // 步骤3：providerID 不存在
r($tester->importTest(array_merge($baseForm, array('providerID' => 1))))               && p() && e('false'); // 步骤4：Subversion url '://' parse_url 失败
r($tester->importTest(array_merge($baseForm, array('providerID' => 2))))               && p() && e('false'); // 步骤5：Subversion url 'abc' parse_url 失败
