#!/usr/bin/env php
<?php

/**

title=测试 repoModel::getHistory();
timeout=0
cid=18064

- 步骤1：正常查询有效revision列表 @2e0dd521b4a29930d5670a2c142a4400d7cffc1a
- 步骤2：查询空revision数组 @empty
- 步骤3：查询不存在的revision @empty
- 步骤4：测试无效repoID参数 @empty
- 步骤5：测试单个revision查询 @2e0dd521b4a29930d5670a2c142a4400d7cffc1a

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');
$revisions = array(
    '2e0dd521b4a29930d5670a2c142a4400d7cffc1a',
    'd30919bdb9b4cf8e2698f4a6a30e41910427c01c',
);
$branch = zenData('ops_repobranch');
$branch->gen(0);

$history = zenData('ops_repohistory');
$history->repo->range('1{2}');
$history->revision->range(implode(',', $revisions));
$history->commit->range('1-2');
$history->comment->range('commit1,commit2');
$history->committer->range('admin{2}');
$history->time->range('1-2')->prefix('2024-01-0')->postfix(' 10:00:00');
$history->gen(2);

$repoTest = new repoModelTest();

r($repoTest->getHistoryTest(1, $revisions))                               && p('0') && e('2e0dd521b4a29930d5670a2c142a4400d7cffc1a'); // 步骤1：正常查询有效revision列表
r($repoTest->getHistoryTest(1, array()))                                 && p()    && e('empty');                                    // 步骤2：查询空revision数组
r($repoTest->getHistoryTest(1, array('nonexistent123', 'invalid456')))   && p()    && e('empty');                                    // 步骤3：查询不存在的revision
r($repoTest->getHistoryTest(999, $revisions))                            && p()    && e('empty');                                    // 步骤4：测试无效repoID参数
r($repoTest->getHistoryTest(1, array_slice($revisions, 0, 1)))           && p('0') && e('2e0dd521b4a29930d5670a2c142a4400d7cffc1a'); // 步骤5：测试单个revision查询
