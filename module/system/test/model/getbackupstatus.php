#!/usr/bin/env php
<?php

/**

title=测试 systemModel::getBackupStatus();
timeout=0
cid=18731

- 执行systemTest模块的getBackupStatusTest方法，参数是$validInstance, 'backup-20241201-120000' 属性result @fail
- 执行systemTest模块的getBackupStatusTest方法，参数是$emptyInstance, 'backup-test' 属性result @fail
- 执行systemTest模块的getBackupStatusTest方法，参数是$validInstance, '' 属性result @fail
- 执行systemTest模块的getBackupStatusTest方法，参数是$validInstance, 'invalid-backup-name' 属性result @fail
- 执行systemTest模块的getBackupStatusTest方法，参数是$validInstance, 'test-backup-cne-error' 属性message @CNE服务器出错

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$systemTest = new systemModelTest();

// 创建测试实例对象
$validInstance = new stdClass();
$validInstance->spaceData = new stdClass();
$validInstance->spaceData->k8space = 'qucikon-system';
$validInstance->k8name = 'zentaopaas';

$emptyInstance = new stdClass();

$invalidInstance = new stdClass();
$invalidInstance->spaceData = new stdClass();
$invalidInstance->spaceData->k8space = 'invalid-space';
$invalidInstance->k8name = 'invalid-name';

r($systemTest->getBackupStatusTest($validInstance, 'backup-20241201-120000')) && p('result') && e('fail');
r($systemTest->getBackupStatusTest($emptyInstance, 'backup-test')) && p('result') && e('fail');
r($systemTest->getBackupStatusTest($validInstance, '')) && p('result') && e('fail');
r($systemTest->getBackupStatusTest($validInstance, 'invalid-backup-name')) && p('result') && e('fail');
r($systemTest->getBackupStatusTest($validInstance, 'test-backup-cne-error')) && p('message') && e('CNE服务器出错');