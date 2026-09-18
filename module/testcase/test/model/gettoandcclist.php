#!/usr/bin/env php
<?php
/**

title=测试 testcaseModel::getToAndCcList();
timeout=0
cid=0

- 测试步骤1：创建人和评审人均存在 @dev1,pm1,qa1
- 测试步骤2：仅创建人 @dev1
- 测试步骤3：仅评审人（多账号） @pm1,qa1
- 测试步骤4：创建人也是评审人之一 @pm1,qa1
- 测试步骤5：创建人和评审人均为空 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

$testcaseTest = new testcaseModelTest();

$normalCase = new stdclass();
$normalCase->openedBy   = 'dev1';
$normalCase->reviewedBy = 'pm1,qa1';

$creatorOnlyCase = new stdclass();
$creatorOnlyCase->openedBy   = 'dev1';
$creatorOnlyCase->reviewedBy = '';

$reviewerOnlyCase = new stdclass();
$reviewerOnlyCase->openedBy   = '';
$reviewerOnlyCase->reviewedBy = 'pm1,qa1';

$overlapCase = new stdclass();
$overlapCase->openedBy   = 'pm1';
$overlapCase->reviewedBy = 'pm1,qa1';

$emptyCase = new stdclass();
$emptyCase->openedBy   = '';
$emptyCase->reviewedBy = '';

r($testcaseTest->getToAndCcListTest($normalCase))       && p('0') && e('dev1,pm1,qa1'); // 测试步骤1：创建人和评审人均存在
r($testcaseTest->getToAndCcListTest($creatorOnlyCase))  && p('0') && e('dev1');         // 测试步骤2：仅创建人
r($testcaseTest->getToAndCcListTest($reviewerOnlyCase)) && p('0') && e('pm1,qa1');      // 测试步骤3：仅评审人（多账号）
r($testcaseTest->getToAndCcListTest($overlapCase))      && p('0') && e('pm1,qa1');      // 测试步骤4：创建人也是评审人之一
r($testcaseTest->getToAndCcListTest($emptyCase))        && p()    && e('0');            // 测试步骤5：创建人和评审人均为空
