#!/usr/bin/env php
<?php

/**

title=测试 cneModel::restore();
timeout=0
cid=0

- 步骤1：使用有效实例ID和备份名进行正常恢复属性code @200
- 步骤2：使用空备份名进行恢复属性code @400
- 步骤3：使用不存在的实例ID进行恢复属性code @404
- 步骤4：使用有效参数但实例ID为0进行恢复属性code @404
- 步骤5：使用不同用户账号参数进行恢复属性code @200

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

su('admin');

$cneTest = new cneTest();

r($cneTest->restoreTest(1, 'backup-20231201-001', 'admin')) && p('code') && e('200');        // 步骤1：使用有效实例ID和备份名进行正常恢复
r($cneTest->restoreTest(1, '', 'admin')) && p('code') && e('400');                           // 步骤2：使用空备份名进行恢复
r($cneTest->restoreTest(999, 'backup-test', 'admin')) && p('code') && e('404');              // 步骤3：使用不存在的实例ID进行恢复
r($cneTest->restoreTest(0, 'backup-zero-test', 'admin')) && p('code') && e('404');           // 步骤4：使用有效参数但实例ID为0进行恢复
r($cneTest->restoreTest(2, 'backup-user-test', 'testuser')) && p('code') && e('200');        // 步骤5：使用不同用户账号参数进行恢复