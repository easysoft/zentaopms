#!/usr/bin/env php
<?php

/**

title=测试 systemModel::deleteBackup();
timeout=0
cid=18729

- 执行systemTest模块的deleteBackupTest方法，参数是$validInstance, 'test-backup-001' 属性result @fail
- 执行systemTest模块的deleteBackupTest方法，参数是$emptyInstance, 'test-backup-002' 属性result @fail
- 执行systemTest模块的deleteBackupTest方法，参数是$validInstance, '' 属性result @fail
- 执行systemTest模块的deleteBackupTest方法，参数是$incompleteInstance, 'test-backup-003' 属性result @fail
- 执行systemTest模块的deleteBackupTest方法，参数是$validInstance, 'valid-backup' 属性message @CNE服务器出错
- 执行systemTest模块的deleteBackupTest方法，参数是$instanceWithoutSpaceData, 'backup-name' 属性result @fail
- 执行systemTest模块的deleteBackupTest方法，参数是$instanceWithoutK8space, 'backup-name' 属性result @fail

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/system.unittest.class.php';

su('admin');

$systemTest = new systemTest();

$validInstance = new stdclass;
$validInstance->k8name = 'test-instance';
$validInstance->chart = 'zentaopaas';
$validInstance->spaceData = new stdclass;
$validInstance->spaceData->k8space = 'default';

$emptyInstance = new stdclass;

$incompleteInstance = new stdclass;
$incompleteInstance->k8name = 'incomplete-instance';

$instanceWithoutSpaceData = new stdclass;
$instanceWithoutSpaceData->k8name = 'test-instance';

$instanceWithoutK8space = new stdclass;
$instanceWithoutK8space->k8name = 'test-instance';
$instanceWithoutK8space->spaceData = new stdclass;

r($systemTest->deleteBackupTest($validInstance, 'test-backup-001')) && p('result') && e('fail');
r($systemTest->deleteBackupTest($emptyInstance, 'test-backup-002')) && p('result') && e('fail');
r($systemTest->deleteBackupTest($validInstance, '')) && p('result') && e('fail');
r($systemTest->deleteBackupTest($incompleteInstance, 'test-backup-003')) && p('result') && e('fail');
r($systemTest->deleteBackupTest($validInstance, 'valid-backup')) && p('message') && e('CNE服务器出错');
r($systemTest->deleteBackupTest($instanceWithoutSpaceData, 'backup-name')) && p('result') && e('fail');
r($systemTest->deleteBackupTest($instanceWithoutK8space, 'backup-name')) && p('result') && e('fail');