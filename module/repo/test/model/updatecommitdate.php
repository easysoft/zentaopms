#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 repoModel::updateCommitDate();
timeout=0
cid=18112

- 步骤1：更新Gitlab版本库属性lastCommit @2023-12-23 11:39:02
- 步骤2：更新Gitea版本库属性lastCommit @~~
- 步骤3：不存在的版本库ID @return empty
- 步骤4：SVN版本库（不在同步范围）属性name @testSvn
- 步骤5：无效的版本库ID（0） @return empty

*/

zenData('repo')->loadYaml('repo')->gen(5);

$repo = new repoModelTest();

r($repo->updateCommitDateTest(1)) && p('lastCommit') && e('2023-12-23 11:39:02'); // 步骤1：更新Gitlab版本库
r($repo->updateCommitDateTest(3)) && p('lastCommit') && e('~~'); // 步骤2：更新Gitea版本库
r($repo->updateCommitDateTest(999)) && p() && e('return empty'); // 步骤3：不存在的版本库ID
r($repo->updateCommitDateTest(4)) && p('name') && e('testSvn'); // 步骤4：SVN版本库（不在同步范围）
r($repo->updateCommitDateTest(0)) && p() && e('return empty'); // 步骤5：无效的版本库ID（0）