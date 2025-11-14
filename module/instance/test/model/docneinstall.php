#!/usr/bin/env php
<?php

/**

title=测试 instanceModel::doCneInstall();
timeout=0
cid=16790

- 执行instanceTest模块的doCneInstallTest方法，参数是null, $validSpace, $validSettingsMap, array  @0
- 执行instanceTest模块的doCneInstallTest方法，参数是$validInstance, $validSpace, $validSettingsMap, array  @Array
- 执行instanceTest模块的doCneInstallTest方法，参数是$validInstance, $validSpace, $validSettingsMap, array  @(
- 执行instanceTest模块的doCneInstallTest方法，参数是$validInstance, $validSpace, $emptySettingsMap, array 属性server @[server] => Array
- 执行instanceTest模块的doCneInstallTest方法，参数是$systemInstance, $validSpace, $validSettingsMap, $snippets, $settings  @(

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/instance.unittest.class.php';

zenData('instance')->loadYaml('instance_docneinstall', false, 2)->gen(5);
zenData('space')->loadYaml('space_docneinstall', false, 2)->gen(3);
zenData('user')->loadYaml('user_docneinstall', false, 2)->gen(2);

su('admin');

$instanceTest = new instanceTest();

$validInstance = new stdclass;
$validInstance->id = 1;
$validInstance->createdBy = 'admin';
$validInstance->k8name = 'test-app-20241201';
$validInstance->chart = 'zentao';
$validInstance->version = '1.0.0';
$validInstance->channel = 'stable';
$validInstance->source = 'cloud';

$systemInstance = new stdclass;
$systemInstance->id = 2;
$systemInstance->createdBy = 'admin';
$systemInstance->k8name = 'system-app-20241201';
$systemInstance->chart = 'system-app';
$systemInstance->version = '1.0.0';
$systemInstance->channel = 'stable';
$systemInstance->source = 'system';

$validSpace = new stdclass;
$validSpace->id = 1;
$validSpace->k8space = 'default';

$validSettingsMap = new stdclass;
$validSettingsMap->ingress = new stdclass;
$validSettingsMap->ingress->enabled = true;
$validSettingsMap->ingress->host = 'test.example.com';

$emptySettingsMap = new stdclass;

$snippets = array('ldapSnippetName' => 'ldap-config');
$settings = array('memory' => '2Gi');

r($instanceTest->doCneInstallTest(null, $validSpace, $validSettingsMap, array(), array())) && p() && e('0');
r($instanceTest->doCneInstallTest($validInstance, $validSpace, $validSettingsMap, array(), array())) && p() && e('Array');
r($instanceTest->doCneInstallTest($validInstance, $validSpace, $validSettingsMap, array(), array())) && p() && e('(');
r($instanceTest->doCneInstallTest($validInstance, $validSpace, $emptySettingsMap, array(), array())) && p('server') && e('[server] => Array');
r($instanceTest->doCneInstallTest($systemInstance, $validSpace, $validSettingsMap, $snippets, $settings)) && p() && e('(');