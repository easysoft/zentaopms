#!/usr/bin/env php
<?php

/**

title=测试 cneModel::restore();
timeout=0
cid=15627

- 测试步骤1:正常恢复请求，实例ID=1，默认账号属性code @200
- 测试步骤2:恢复请求，实例ID=2，指定账号testuser第data条的account属性 @testuser
- 测试步骤3:恢复请求，实例ID=3，不同备份名称第data条的backup_name属性 @backup-20241207-001
- 测试步骤4:不存在的实例ID=999属性code @404
- 测试步骤5:空备份名称属性code @400

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

$cneTest = new cneTest();

r($cneTest->restoreTest(1, 'backup-test-001', '')) && p('code') && e('200'); // 测试步骤1:正常恢复请求，实例ID=1，默认账号
r($cneTest->restoreTest(2, 'backup-test-002', 'testuser')) && p('data:account') && e('testuser'); // 测试步骤2:恢复请求，实例ID=2，指定账号testuser
r($cneTest->restoreTest(3, 'backup-20241207-001', '')) && p('data:backup_name') && e('backup-20241207-001'); // 测试步骤3:恢复请求，实例ID=3，不同备份名称
r($cneTest->restoreTest(999, 'backup-test-003', '')) && p('code') && e('404'); // 测试步骤4:不存在的实例ID=999
r($cneTest->restoreTest(1, '', '')) && p('code') && e('400'); // 测试步骤5:空备份名称