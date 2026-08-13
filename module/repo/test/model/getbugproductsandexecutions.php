#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 repoModel->getBugProductsAndExecutions();
timeout=0
cid=0

- 空数组测试 @0
- 单个bug ID @1
- 多个bug IDs @2
- 不存在的bug ID @0
- 混合有效无效ID @1

*/

su('admin');

zendata('bug')->loadYaml('bug_getcommitsbyobject', false, 2)->gen(5);

$repoTest = new repoModelTest();

r($repoTest->getBugProductsAndExecutionsTest(array())) && p() && e('0');               // 空数组测试
r($repoTest->getBugProductsAndExecutionsTest(array(1))) && p() && e('1');               // 单个bug ID
r($repoTest->getBugProductsAndExecutionsTest(array(1, 2))) && p() && e('2');            // 多个bug IDs
r($repoTest->getBugProductsAndExecutionsTest(array(999))) && p() && e('0');             // 不存在的bug ID
r($repoTest->getBugProductsAndExecutionsTest(array(1, 999))) && p() && e('1');          // 混合有效无效ID