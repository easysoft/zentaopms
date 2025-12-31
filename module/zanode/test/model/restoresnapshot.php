#!/usr/bin/env php
<?php
declare(strict_types=1);

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zanode.unittest.class.php';

su('admin');
$zanodeTest = new zanodeTest();

/**

title=测试 zanodeModel::restoreSnapshot();
timeout=0
cid=0

- 步骤1：快照状态为completed，HTTP连接失败 @failed
- 步骤2：快照状态为restoring，正在还原中 @快照正在还原中
- 步骤3：快照状态为creating，不可用 @快照不可用
- 步骤4：快照状态为running，不可用 @快照不可用
- 步骤5：快照状态为failed，不可用 @快照不可用

*/

r($zanodeTest->restoreSnapshotTest(2, 1)) && p() && e('failed'); // 步骤1：快照状态为completed，HTTP连接失败
r($zanodeTest->restoreSnapshotTest(2, 2)) && p() && e('快照正在还原中'); // 步骤2：快照状态为restoring，正在还原中
r($zanodeTest->restoreSnapshotTest(2, 3)) && p() && e('快照不可用'); // 步骤3：快照状态为creating，不可用
r($zanodeTest->restoreSnapshotTest(2, 4)) && p() && e('快照不可用'); // 步骤4：快照状态为running，不可用
r($zanodeTest->restoreSnapshotTest(2, 5)) && p() && e('快照不可用'); // 步骤5：快照状态为failed，不可用
