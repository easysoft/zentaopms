#!/usr/bin/env php
<?php
/**

title=测试 projectModel::getToAndCcList();
timeout=0
cid=0

- 测试步骤1：创建人和项目负责人均存在 >> 收件人为创建人和项目负责人
- 测试步骤2：仅项目负责人 >> 收件人为项目负责人
- 测试步骤3：仅创建人 >> 收件人为创建人
- 测试步骤4：创建人和项目负责人相同 >> 收件人去重后只有一人
- 测试步骤5：创建人和项目负责人均为空 >> 返回false不发送

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

$projectTest = new projectModelTest();

$normalProject = new stdclass();
$normalProject->openedBy = 'dev1';
$normalProject->PM       = 'pm1';

$pmOnlyProject = new stdclass();
$pmOnlyProject->openedBy = '';
$pmOnlyProject->PM       = 'pm1';

$creatorOnlyProject = new stdclass();
$creatorOnlyProject->openedBy = 'dev1';
$creatorOnlyProject->PM       = '';

$sameUserProject = new stdclass();
$sameUserProject->openedBy = 'pm1';
$sameUserProject->PM       = 'pm1';

$emptyProject = new stdclass();
$emptyProject->openedBy = '';
$emptyProject->PM       = '';

r($projectTest->getToAndCcListTest($normalProject))      && p('0') && e('dev1,pm1'); // 测试步骤1：创建人和项目负责人均存在
r($projectTest->getToAndCcListTest($pmOnlyProject))      && p('0') && e('pm1');      // 测试步骤2：仅项目负责人
r($projectTest->getToAndCcListTest($creatorOnlyProject)) && p('0') && e('dev1');     // 测试步骤3：仅创建人
r($projectTest->getToAndCcListTest($sameUserProject))    && p('0') && e('pm1');      // 测试步骤4：创建人和项目负责人相同
r($projectTest->getToAndCcListTest($emptyProject))       && p()    && e('0');        // 测试步骤5：均为空返回false
