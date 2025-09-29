#!/usr/bin/env php
<?php

/**

title=测试 aiModel::countLatestMiniPrograms();
timeout=0
cid=0

- 执行aiTest模块的countLatestMiniProgramsTest方法  @3
- 执行aiTest模块的countLatestMiniProgramsTest方法  @3
- 执行aiTest模块的countLatestMiniProgramsTest方法  @3
- 执行aiTest模块的countLatestMiniProgramsTest方法  @3
- 执行aiTest模块的countLatestMiniProgramsTest方法  @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

su('admin');
$aiTest = new aiTest();

r($aiTest->countLatestMiniProgramsTest()) && p() && e('3');
r($aiTest->countLatestMiniProgramsTest()) && p() && e('3');
r($aiTest->countLatestMiniProgramsTest()) && p() && e('3');
r($aiTest->countLatestMiniProgramsTest()) && p() && e('3');
r($aiTest->countLatestMiniProgramsTest()) && p() && e('3');