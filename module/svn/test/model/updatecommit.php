#!/usr/bin/env php
<?php

/**

title=测试 svnModel::updateCommit();
timeout=0
cid=18726

- 测试步骤1：初始化仓库应返回true @true
- 测试步骤2：已同步仓库的正常updateCommit操作
 - 属性repo @1
 - 属性commit @1
- 测试步骤3：带有提交任务触发组的操作属性repo @1
- 测试步骤4：不存在仓库ID返回0 @0
- 测试步骤5：验证仓库历史记录数量 @1
- 测试步骤6：获取最新提交记录属性commit @1
- 测试步骤7：正常仓库应返回true @true

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$repo = zenData('repo')->loadYaml('repo_updatecommit', false, 2);
$repo->gen(5);
$repoHistory = zenData('repohistory')->loadYaml('repohistory_updatecommit', false, 2);
$repoHistory->gen(10);

su('admin');

$svn = new svnModelTest();

r($svn->updateCommitTest(3, array(), 'boolean')) && p() && e('true'); // 测试步骤1：初始化仓库应返回true
r($svn->updateCommitTest(1, array(), 'history')) && p('repo,commit') && e('1,1'); // 测试步骤2：已同步仓库的正常updateCommit操作
r($svn->updateCommitTest(1, array(1 => array('id' => 1, 'comment' => 'task')), 'history')) && p('repo') && e('1'); // 测试步骤3：带有提交任务触发组的操作
r($svn->updateCommitTest(999, array(), 'boolean')) && p() && e('0'); // 测试步骤4：不存在仓库ID返回0
r($svn->updateCommitTest(1, array(), 'count')) && p() && e('1'); // 测试步骤5：验证仓库历史记录数量
r($svn->updateCommitTest(1, array(), 'latest')) && p('commit') && e('1'); // 测试步骤6：获取最新提交记录
r($svn->updateCommitTest(2, array(), 'boolean')) && p() && e('true'); // 测试步骤7：正常仓库应返回true