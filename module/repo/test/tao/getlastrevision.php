#!/usr/bin/env php
<?php

/**

title=测试 repoTao::getLastRevision();
timeout=0
cid=18117

- 执行repoTest模块的getLastRevisionTest方法，参数是1  @2023-12-13 19:00:25
- 执行repoTest模块的getLastRevisionTest方法，参数是3  @2023-12-18 19:00:25
- 执行repoTest模块的getLastRevisionTest方法，参数是2  @0
- 执行repoTest模块的getLastRevisionTest方法，参数是999  @0
- 执行repoTest模块的getLastRevisionTest方法  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';

zenData('repo')->loadYaml('repo')->gen(4);
zenData('repohistory')->loadYaml('repohistory')->gen(3);

su('admin');

$repoTest = new repoTest();

r($repoTest->getLastRevisionTest(1)) && p() && e('2023-12-13 19:00:25');
r($repoTest->getLastRevisionTest(3)) && p() && e('2023-12-18 19:00:25');
r($repoTest->getLastRevisionTest(2)) && p() && e('0');
r($repoTest->getLastRevisionTest(999)) && p() && e('0');
r($repoTest->getLastRevisionTest(0)) && p() && e('0');