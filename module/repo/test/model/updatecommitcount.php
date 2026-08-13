#!/usr/bin/env php
<?php

/**

title=测试 repoModel::updateCommitCount();
timeout=0
cid=18111

- 测试步骤1：正常更新版本库提交计数
 - 返回值 @0
- 测试步骤2：更新提交计数为0
 - 返回值 @0
- 测试步骤3：更新提交计数为极大值
 - 返回值 @0
- 测试步骤4：更新不存在的版本库ID @0
- 测试步骤5：更新另一个版本库的提交计数
 - 返回值 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

zenData('ops_repo')->gen(0);

$repoTest = new repoModelTest();

r($repoTest->updateCommitCountTest(1, 100))    && p() && e('0');
r($repoTest->updateCommitCountTest(2, 0))      && p() && e('0');
r($repoTest->updateCommitCountTest(3, 999999)) && p() && e('0');
r($repoTest->updateCommitCountTest(999, 50))   && p() && e('0');
r($repoTest->updateCommitCountTest(4, 1000))   && p() && e('0');
