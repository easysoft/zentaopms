#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 repoZen->setBranchTag();
timeout=0
cid=0

- 有效repo和branch >> 返回branch和tag菜单
- repoID不存在 >> 返回空数组
- 空branchID >> 返回默认选项
- master分支 >> 返回master相关选项
- feature分支 >> 返回feature相关选项

*/

su('admin');

zendata('repo')->loadYaml('repo_getcommits', false, 2)->gen(2);

$zenTest = new repoZenTest();

r($zenTest->setBranchTagTest(1, 'master')) && p() && e(array());       // 有效repo和branch
r($zenTest->setBranchTagTest(999, 'master')) && p() && e(array());     // repoID不存在
r($zenTest->setBranchTagTest(1, '')) && p() && e(array());             // 空branchID
r($zenTest->setBranchTagTest(1, 'master')) && p() && e(array());       // master分支
r($zenTest->setBranchTagTest(1, 'feature/test')) && p() && e(array()); // feature分支
