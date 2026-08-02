#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';
su('admin');

/**

title=测试 repoTao->getlastrevision();
timeout=0
cid=0

- 方法存在性检查 >> 1
- repoTaoTest 类存在 >> 1
- repoTao 类存在 >> 1
- 再次方法存在检查 >> 1
- 类存在性确认 >> 1

*/

$repoTest = new repoTaoTest();
r($repoTest->getLastRevisionAvailableTest(0))   && p() && e('1');
r($repoTest->getLastRevisionAvailableTest(1))   && p() && e('1');
r($repoTest->getLastRevisionAvailableTest(-1))  && p() && e('1');
r($repoTest->getLastRevisionAvailableTest(999)) && p() && e('1');
r($repoTest->getLastRevisionAvailableTest(2))   && p() && e('1');
