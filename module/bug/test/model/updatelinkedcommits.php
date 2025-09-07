#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';
su('admin');

zenData('bug')->gen(10);
zenData('relation')->gen(0);

/**

title=bugModel->updateLinkedCommits();
timeout=0
cid=0

- 正常情况下更新bug关联的提交记录 @1
- bugID为0的边界值情况 @1
- repoID为0的边界值情况 @1
- revisions为空数组的边界值情况 @1
- 不存在的bugID情况 @1

*/

$bug = new bugTest();
r($bug->updateLinkedCommitsTest(1, 1, array('rev001', 'rev002'))) && p() && e('1'); // 正常情况下更新bug关联的提交记录
r($bug->updateLinkedCommitsTest(0, 1, array('rev001'))) && p() && e('1'); // bugID为0的边界值情况
r($bug->updateLinkedCommitsTest(1, 0, array('rev001'))) && p() && e('1'); // repoID为0的边界值情况
r($bug->updateLinkedCommitsTest(1, 1, array())) && p() && e('1'); // revisions为空数组的边界值情况
r($bug->updateLinkedCommitsTest(999, 1, array('rev001'))) && p() && e('1'); // 不存在的bugID情况