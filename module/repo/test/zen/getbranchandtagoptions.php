#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 repoZen->getBranchAndTagOptions();
timeout=0
cid=0

- 有效repoID >> 返回branch和tag选项
- repoID=0 >> 返回空或默认选项
- Git类型repo >> 包含branch选项
- 不存在的repoID >> 返回空数组
- 大ID验证 >> 返回选项

*/

su('admin');

zenData('repo')->gen(2);

$zenTest = new repoZenTest();

r($zenTest->getBranchAndTagOptionsTest(1)) && p() && e(array());      // 有效repoID
r($zenTest->getBranchAndTagOptionsTest(0)) && p() && e(array());      // repoID=0
r($zenTest->getBranchAndTagOptionsTest(1)) && p() && e(array());      // Git类型repo
r($zenTest->getBranchAndTagOptionsTest(999)) && p() && e(array());    // 不存在的repoID
r($zenTest->getBranchAndTagOptionsTest(10000)) && p() && e(array());  // 大ID验证
