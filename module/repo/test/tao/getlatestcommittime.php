#!/usr/bin/env php
<?php

/**

title=测试 repoTao::getLatestCommitTime();
timeout=0
cid=18118

- 测试步骤1：正常获取版本库1的HEAD提交时间 @2023-12-13 19:00:25
- 测试步骤2：正常获取版本库3的HEAD提交时间 @2023-12-18 19:00:25
- 测试步骤3：指定特定revision获取提交时间 @2023-12-13 13:04:27
- 测试步骤4：测试不存在的分支 @0
- 测试步骤5：测试无效版本库ID的边界情况 @0
- 测试步骤6：测试不存在的revision @0
- 测试步骤7：测试版本库ID为0的边界情况 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('repo')->loadYaml('repo')->gen(4);
zenData('repohistory')->loadYaml('repohistory')->gen(3);

su('admin');

$repoTest = new repoTaoTest();

r($repoTest->getLatestCommitTimeTest(1, 'HEAD', '')) && p() && e('2023-12-13 19:00:25'); // 测试步骤1：正常获取版本库1的HEAD提交时间
r($repoTest->getLatestCommitTimeTest(3, 'HEAD', '')) && p() && e('2023-12-18 19:00:25'); // 测试步骤2：正常获取版本库3的HEAD提交时间
r($repoTest->getLatestCommitTimeTest(3, '0dbb150d4904c9a9d5a804b6cdddea3cb3d856bb', '')) && p() && e('2023-12-13 13:04:27'); // 测试步骤3：指定特定revision获取提交时间
r($repoTest->getLatestCommitTimeTest(1, 'HEAD', 'develop')) && p() && e('0'); // 测试步骤4：测试不存在的分支
r($repoTest->getLatestCommitTimeTest(999, 'HEAD', '')) && p() && e('0'); // 测试步骤5：测试无效版本库ID的边界情况
r($repoTest->getLatestCommitTimeTest(1, 'nonexistent-revision', '')) && p() && e('0'); // 测试步骤6：测试不存在的revision
r($repoTest->getLatestCommitTimeTest(0, 'HEAD', '')) && p() && e('0'); // 测试步骤7：测试版本库ID为0的边界情况