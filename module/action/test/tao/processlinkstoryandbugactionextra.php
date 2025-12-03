#!/usr/bin/env php
<?php

/**

title=测试 actionTao::processLinkStoryAndBugActionExtra();
timeout=0
cid=0

- 步骤1:测试单个需求ID(ID为1) @1
- 步骤2:测试多个需求ID(ID为1,2,3) @1
- 步骤3:测试单个bug ID(ID为1) @1
- 步骤4:测试多个bug ID(ID为1,2,3) @1
- 步骤5:测试空字符串输入 @1
- 步骤6:测试不存在的ID(ID为999) @1
- 步骤7:测试混合存在和不存在的ID(ID为1,999,2) @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

su('admin');

$actionTest = new actionTaoTest();

r(strlen($actionTest->processLinkStoryAndBugActionExtraTest('1', 'story', 'view')->extra) > 0) && p() && e('1'); // 步骤1:测试单个需求ID(ID为1)
r(strpos($actionTest->processLinkStoryAndBugActionExtraTest('1,2,3', 'story', 'view')->extra, ',') !== false) && p() && e('1'); // 步骤2:测试多个需求ID(ID为1,2,3)
r(strlen($actionTest->processLinkStoryAndBugActionExtraTest('1', 'bug', 'view')->extra) > 0) && p() && e('1'); // 步骤3:测试单个bug ID(ID为1)
r(strpos($actionTest->processLinkStoryAndBugActionExtraTest('1,2,3', 'bug', 'view')->extra, ',') !== false) && p() && e('1'); // 步骤4:测试多个bug ID(ID为1,2,3)
r(strlen($actionTest->processLinkStoryAndBugActionExtraTest('', 'story', 'view')->extra) > 0) && p() && e('1'); // 步骤5:测试空字符串输入
r(strlen($actionTest->processLinkStoryAndBugActionExtraTest('999', 'story', 'view')->extra) > 0) && p() && e('1'); // 步骤6:测试不存在的ID(ID为999)
r(strpos($actionTest->processLinkStoryAndBugActionExtraTest('1,999,2', 'story', 'view')->extra, ',') !== false) && p() && e('1'); // 步骤7:测试混合存在和不存在的ID(ID为1,999,2)