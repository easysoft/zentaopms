#!/usr/bin/env php
<?php
/**

title=测试 productModel::getToAndCcList();
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
$product = new productTest('admin');

$normalProduct = new stdclass();
$normalProduct->createdBy = 'dev1';
$normalProduct->PO        = 'po1';

$poOnlyProduct = new stdclass();
$poOnlyProduct->createdBy = '';
$poOnlyProduct->PO        = 'po1';

$creatorOnlyProduct = new stdclass();
$creatorOnlyProduct->createdBy = 'dev1';
$creatorOnlyProduct->PO        = '';

$sameUserProduct = new stdclass();
$sameUserProduct->createdBy = 'po1';
$sameUserProduct->PO        = 'po1';

$emptyProduct = new stdclass();
$emptyProduct->createdBy = '';
$emptyProduct->PO        = '';

r($product->getToAndCcListTest($normalProduct))      && p('0') && e('dev1,po1'); // 测试步骤1：创建人和产品负责人均存在
r($product->getToAndCcListTest($poOnlyProduct))      && p('0') && e('po1');      // 测试步骤2：仅产品负责人
r($product->getToAndCcListTest($creatorOnlyProduct)) && p('0') && e('dev1');     // 测试步骤3：仅创建人
r($product->getToAndCcListTest($sameUserProduct))    && p('0') && e('po1');      // 测试步骤4：创建人和产品负责人相同
r($product->getToAndCcListTest($emptyProduct))       && p()    && e('0');        // 测试步骤5：均为空返回false
