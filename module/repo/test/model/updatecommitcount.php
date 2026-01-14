#!/usr/bin/env php
<?php

/**

title=测试 repoModel::updateCommitCount();
timeout=0
cid=18111

- 测试步骤1：正常更新版本库提交计数
 - 属性id @1
 - 属性commits @100
- 测试步骤2：更新提交计数为0
 - 属性id @2
 - 属性commits @0
- 测试步骤3：更新提交计数为极大值
 - 属性id @3
 - 属性commits @999999
- 测试步骤4：更新不存在的版本库ID @0
- 测试步骤5：更新另一个版本库的提交计数
 - 属性id @4
 - 属性commits @1000

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('repo')->loadYaml('repo')->gen(5);

su('admin');

$repoTest = new repoModelTest();

r($repoTest->updateCommitCountTest(1, 100)) && p('id,commits') && e('1,100');    // 测试步骤1：正常更新版本库提交计数
r($repoTest->updateCommitCountTest(2, 0)) && p('id,commits') && e('2,0');        // 测试步骤2：更新提交计数为0
r($repoTest->updateCommitCountTest(3, 999999)) && p('id,commits') && e('3,999999'); // 测试步骤3：更新提交计数为极大值
r($repoTest->updateCommitCountTest(999, 50)) && p() && e('0');                  // 测试步骤4：更新不存在的版本库ID
r($repoTest->updateCommitCountTest(4, 1000)) && p('id,commits') && e('4,1000');  // 测试步骤5：更新另一个版本库的提交计数