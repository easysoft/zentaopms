#!/usr/bin/env php
<?php

/**

title=测试 gitfoxZen::buildGitFoxScript();
timeout=0
cid=0

- 步骤 1：生成安装脚本返回路径 @1
- 步骤 2：安装脚本文件存在 @1
- 步骤 3：安装脚本包含安装命令 @1
- 步骤 4：生成升级脚本返回路径 @1
- 步骤 5：升级脚本文件存在 @1
- 步骤 6：升级脚本包含升级命令 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$gitfoxZenTest = new gitfoxZenTest();

r(!empty($gitfoxZenTest->buildGitFoxScriptTest('install'))) && p() && e('1');            // 步骤 1
r($gitfoxZenTest->buildGitFoxScriptExistsTest('install')) && p() && e('1');              // 步骤 2
r($gitfoxZenTest->buildGitFoxScriptContentTest('install', '/gitfox" install')) && p() && e('1'); // 步骤 3
r(!empty($gitfoxZenTest->buildGitFoxScriptTest('upgrade'))) && p() && e('1');            // 步骤 4
r($gitfoxZenTest->buildGitFoxScriptExistsTest('upgrade')) && p() && e('1');              // 步骤 5
r($gitfoxZenTest->buildGitFoxScriptContentTest('upgrade', '/gitfox" upgrade')) && p() && e('1'); // 步骤 6
