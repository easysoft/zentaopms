#!/usr/bin/env php
<?php
/**

title=测试 productplanModel::getToAndCcList();
timeout=0
cid=0

- 测试步骤1：创建人和产品负责人均存在 @dev1,po1
- 测试步骤2：仅产品负责人 @po1
- 测试步骤3：仅创建人 @dev1
- 测试步骤4：创建人和产品负责人相同 @po1
- 测试步骤5：均为空返回false @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$product = zenData('product');
$product->loadYaml('product');
$product->PO->range('po1');
$product->gen(1);

$planTest = new productplanModelTest('admin');

$normalPlan = new stdclass();
$normalPlan->product   = 1;
$normalPlan->createdBy = 'dev1';

$poOnlyPlan = new stdclass();
$poOnlyPlan->product   = 1;
$poOnlyPlan->createdBy = '';

$creatorOnlyPlan = new stdclass();
$creatorOnlyPlan->product   = 999;
$creatorOnlyPlan->createdBy = 'dev1';

$sameUserPlan = new stdclass();
$sameUserPlan->product   = 1;
$sameUserPlan->createdBy = 'po1';

$emptyPlan = new stdclass();
$emptyPlan->product   = 999;
$emptyPlan->createdBy = '';

r($planTest->getToAndCcListTest($normalPlan))      && p('0') && e('dev1,po1'); // 测试步骤1：创建人和产品负责人均存在
r($planTest->getToAndCcListTest($poOnlyPlan))      && p('0') && e('po1');      // 测试步骤2：仅产品负责人
r($planTest->getToAndCcListTest($creatorOnlyPlan)) && p('0') && e('dev1');     // 测试步骤3：仅创建人
r($planTest->getToAndCcListTest($sameUserPlan))    && p('0') && e('po1');      // 测试步骤4：创建人和产品负责人相同
r($planTest->getToAndCcListTest($emptyPlan))       && p()    && e('0');        // 测试步骤5：均为空返回false
