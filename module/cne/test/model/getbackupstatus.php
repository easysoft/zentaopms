#!/usr/bin/env php
<?php

/**

title=测试 cneModel::getBackupStatus();
timeout=0
cid=15615

- 测试步骤1:正常获取备份状态,实例ID=1,备份名称=backup-001属性code @200
- 测试步骤2:正常获取备份状态,实例ID=2,备份名称=backup-002第data条的status属性 @completed
- 测试步骤3:获取备份状态,实例ID=3,备份名称=backup-test第data条的backup_name属性 @backup-test
- 测试步骤4:获取备份状态,无效实例ID=0,备份名称=backup-001属性code @400
- 测试步骤5:获取备份状态,不存在实例ID=999,备份名称=backup-001属性code @404
- 测试步骤6:获取备份状态,实例ID=1,空备份名称 @1
- 测试步骤7:获取备份状态,实例ID=1,长备份名称属性code @200

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

$cneTest = new cneTest();

r($cneTest->getBackupStatusTest(1, 'backup-001')) && p('code') && e('200'); // 测试步骤1:正常获取备份状态,实例ID=1,备份名称=backup-001
r($cneTest->getBackupStatusTest(2, 'backup-002')) && p('data:status') && e('completed'); // 测试步骤2:正常获取备份状态,实例ID=2,备份名称=backup-002
r($cneTest->getBackupStatusTest(3, 'backup-test')) && p('data:backup_name') && e('backup-test'); // 测试步骤3:获取备份状态,实例ID=3,备份名称=backup-test
r($cneTest->getBackupStatusTest(0, 'backup-001')) && p('code') && e('400'); // 测试步骤4:获取备份状态,无效实例ID=0,备份名称=backup-001
r($cneTest->getBackupStatusTest(999, 'backup-001')) && p('code') && e('404'); // 测试步骤5:获取备份状态,不存在实例ID=999,备份名称=backup-001
r(is_object($cneTest->getBackupStatusTest(1, ''))) && p() && e('1'); // 测试步骤6:获取备份状态,实例ID=1,空备份名称
r($cneTest->getBackupStatusTest(1, 'backup-very-long-name-test-12345')) && p('code') && e('200'); // 测试步骤7:获取备份状态,实例ID=1,长备份名称