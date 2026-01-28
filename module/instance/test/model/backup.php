#!/usr/bin/env php
<?php

/**

title=测试 instanceModel::backup();
timeout=0
cid=16779

- 执行instanceTest模块的backupTest方法，参数是$validInstance, $validUser  @0
- 执行instanceTest模块的backupTest方法，参数是$emptyInstance, $validUser  @0
- 执行instanceTest模块的backupTest方法，参数是$validInstance, $emptyUser  @0
- 执行instanceTest模块的backupTest方法，参数是$invalidInstance, $validUser  @0
- 执行instanceTest模块的backupTest方法，参数是$validInstance, $invalidUser  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('instance')->loadYaml('instance_backup', false, 2)->gen(5);
zenData('user')->loadYaml('user_backup', false, 2)->gen(5);

su('admin');

$instanceTest = new instanceModelTest();

$validInstance = new stdClass();
$validInstance->id = 1;
$validInstance->name = 'test-instance';
$validInstance->k8name = 'test-k8name';
$validInstance->chart = 'zentao';
$validInstance->status = 'running';
$validInstance->space = 1;
$validInstance->spaceData = new stdClass();
$validInstance->spaceData->k8space = 'default';

$validUser = new stdClass();
$validUser->account = 'admin';

$emptyInstance = new stdClass();
$emptyInstance->spaceData = new stdClass();
$emptyInstance->spaceData->k8space = 'default';
$emptyInstance->k8name = '';

$emptyUser = new stdClass();
$emptyUser->account = '';

$invalidInstance = new stdClass();
$invalidInstance->id = 0;
$invalidInstance->name = '';
$invalidInstance->k8name = '';
$invalidInstance->spaceData = new stdClass();
$invalidInstance->spaceData->k8space = 'default';

$invalidUser = new stdClass();
$invalidUser->account = '';

r($instanceTest->backupTest($validInstance, $validUser)) && p() && e('0');
r($instanceTest->backupTest($emptyInstance, $validUser)) && p() && e('0');
r($instanceTest->backupTest($validInstance, $emptyUser)) && p() && e('0');
r($instanceTest->backupTest($invalidInstance, $validUser)) && p() && e('0');
r($instanceTest->backupTest($validInstance, $invalidUser)) && p() && e('0');