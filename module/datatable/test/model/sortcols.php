#!/usr/bin/env php
<?php

/**

title=测试 datatableModel::sortCols();
timeout=0
cid=15948

- 测试步骤1：正常排序order值小的在前 @-1
- 测试步骤2：正常排序order值大的在前 @1
- 测试步骤3：相同order值的排序 @0
- 测试步骤4：缺少order字段的数组排序 @0
- 测试步骤5：边界值测试负数和正数排序 @-11
- 测试步骤6：大数值差异的排序测试 @-999
- 测试步骤7：零值order字段的排序测试 @-1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$datatableTest = new datatableModelTest();

r($datatableTest->sortColsTest(array('order' => 1), array('order' => 2))) && p() && e('-1');
r($datatableTest->sortColsTest(array('order' => 2), array('order' => 1))) && p() && e('1');
r($datatableTest->sortColsTest(array('order' => 1), array('order' => 1))) && p() && e('0');
r($datatableTest->sortColsTest(array(), array('order' => 1))) && p() && e('0');
r($datatableTest->sortColsTest(array('order' => -10), array('order' => 1))) && p() && e('-11');
r($datatableTest->sortColsTest(array('order' => 1), array('order' => 1000))) && p() && e('-999');
r($datatableTest->sortColsTest(array('order' => 0), array('order' => 1))) && p() && e('-1');