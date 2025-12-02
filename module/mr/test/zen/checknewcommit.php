#!/usr/bin/env php
<?php

/**

title=测试 mrZen::checkNewCommit();
timeout=0
cid=17265

- 执行mrTest模块的checkNewCommitTest方法，参数是'gitlab', $mockCommits, '2025-11-10 10:00:00'  @1
- 执行mrTest模块的checkNewCommitTest方法，参数是'gitlab', $mockCommits, '2025-11-10 10:00:00'  @0
- 执行mrTest模块的checkNewCommitTest方法，参数是'gitea', $mockCommits, '2025-11-10 10:00:00'  @1
- 执行mrTest模块的checkNewCommitTest方法，参数是'gitea', $mockCommits, '2025-11-10 10:00:00'  @0
- 执行mrTest模块的checkNewCommitTest方法，参数是'gitlab', $mockCommits, '2025-11-10 10:00:00'  @0
- 执行mrTest模块的checkNewCommitTest方法，参数是'gogs', $mockCommits, '2025-11-10 10:00:00'  @1
- 执行mrTest模块的checkNewCommitTest方法，参数是'gitlab', $mockCommits, '2025-11-10 10:00:00'  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

global $app;
$app->setMethodName('view');

zenData('mr')->gen(0);
zenData('repo')->gen(0);

su('admin');

$mrTest = new mrZenTest();

// 测试步骤1: GitLab类型,有新提交(committed_date > lastTime)
$mockCommits = array((object)array('committed_date' => '2025-11-10 12:00:00', 'short_id' => 'abc123'));
r($mrTest->checkNewCommitTest('gitlab', $mockCommits, '2025-11-10 10:00:00')) && p() && e('1');

// 测试步骤2: GitLab类型,无新提交(committed_date <= lastTime)
$mockCommits = array((object)array('committed_date' => '2025-11-10 08:00:00', 'short_id' => 'def456'));
r($mrTest->checkNewCommitTest('gitlab', $mockCommits, '2025-11-10 10:00:00')) && p() && e('0');

// 测试步骤3: Gitea类型,有新提交(author.committer.date > lastTime)
$mockCommits = array((object)array('sha' => 'xyz789', 'author' => (object)array('committer' => (object)array('date' => '2025-11-10 12:00:00'))));
r($mrTest->checkNewCommitTest('gitea', $mockCommits, '2025-11-10 10:00:00')) && p() && e('1');

// 测试步骤4: Gitea类型,无新提交(author.committer.date <= lastTime)
$mockCommits = array((object)array('sha' => 'uvw999', 'author' => (object)array('committer' => (object)array('date' => '2025-11-10 08:00:00'))));
r($mrTest->checkNewCommitTest('gitea', $mockCommits, '2025-11-10 10:00:00')) && p() && e('0');

// 测试步骤5: 没有提交日志(空数组)
$mockCommits = array();
r($mrTest->checkNewCommitTest('gitlab', $mockCommits, '2025-11-10 10:00:00')) && p() && e('0');

// 测试步骤6: Gogs类型,有新提交(author.committer.date > lastTime)
$mockCommits = array((object)array('sha' => 'gogs123', 'author' => (object)array('committer' => (object)array('date' => '2025-11-10 12:00:00'))));
r($mrTest->checkNewCommitTest('gogs', $mockCommits, '2025-11-10 10:00:00')) && p() && e('1');

// 测试步骤7: 提交时间等于lastTime
$mockCommits = array((object)array('committed_date' => '2025-11-10 10:00:00', 'short_id' => 'equal123'));
r($mrTest->checkNewCommitTest('gitlab', $mockCommits, '2025-11-10 10:00:00')) && p() && e('0');