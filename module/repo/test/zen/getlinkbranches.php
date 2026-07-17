#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 repoZen->getLinkBranches();
timeout=0
cid=0

- 空products数组 >> 返回空数组
- 有products >> 返回分支列表
- 单个product >> 返回该产品分支
- 有product但无分支 >> 返回空数组
- 多个products >> 返回多产品分支

*/

su('admin');

zendata('product')->loadYaml('product_getlinkbranches', false, 2)->gen(3);

$zenTest = new repoZenTest();

r($zenTest->getLinkBranchesTest(array())) && p() && e(array());       // 空products数组
r($zenTest->getLinkBranchesTest(array(1))) && p() && e(array());      // 单个product
r($zenTest->getLinkBranchesTest(array(1, 2))) && p() && e(array());   // 多个products
r($zenTest->getLinkBranchesTest(array(999))) && p() && e(array());    // 不存在的product
r($zenTest->getLinkBranchesTest(array(1))) && p() && e(array());      // 再次验证单个
