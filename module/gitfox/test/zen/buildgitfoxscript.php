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
- 步骤 7：Windows 系统安装脚本扩展名为 bat @bat
- 步骤 8：Windows 系统安装脚本使用 Windows 安装包 @1
- 步骤 9：Windows 系统安装脚本包含 exe 安装命令 @1
- 步骤 10：Windows 系统升级脚本包含 exe 升级命令 @1
- 步骤 11：ARM 架构安装脚本使用 ARM 安装包 @1
- 步骤 12：macOS 系统安装脚本扩展名为 sh @sh
- 步骤 13：Linux 安装脚本保留脚本内变量 @1
- 步骤 14：Linux 安装脚本替换安装目录 @1
- 步骤 15：Linux 安装脚本不残留占位符 @0
- 步骤 16：Linux 安装脚本保留下载地址变量 @1
- 步骤 17：Windows 安装脚本保留批处理变量 @1
- 步骤 18：Windows 安装脚本替换安装目录 @1
- 步骤 19：Windows 系统名大小写不敏感 @bat
- 步骤 20：其他类 Unix 系统安装脚本扩展名为 sh @sh

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
r($gitfoxZenTest->buildGitFoxScriptExtensionTest('install', 'WINNT', 'AMD64')) && p() && e('bat'); // 步骤 7
r($gitfoxZenTest->buildGitFoxScriptContentTest('install', 'windows-amd64.zip', 'WINNT', 'AMD64')) && p() && e('1'); // 步骤 8
r($gitfoxZenTest->buildGitFoxScriptContentTest('install', 'gitfox.exe" install', 'WINNT', 'AMD64')) && p() && e('1'); // 步骤 9
r($gitfoxZenTest->buildGitFoxScriptContentTest('upgrade', 'gitfox.exe" upgrade', 'WINNT', 'AMD64')) && p() && e('1'); // 步骤 10
r($gitfoxZenTest->buildGitFoxScriptContentTest('install', 'linux-arm64.zip', 'Linux', 'aarch64')) && p() && e('1'); // 步骤 11
r($gitfoxZenTest->buildGitFoxScriptExtensionTest('install', 'Darwin', 'arm64')) && p() && e('sh'); // 步骤 12
r($gitfoxZenTest->buildGitFoxScriptContentTest('install', '[ ! -d "${INSTALL_DIR}" ]')) && p() && e('1'); // 步骤 13
r($gitfoxZenTest->buildGitFoxScriptInstallDirTest('install')) && p() && e('1'); // 步骤 14
r($gitfoxZenTest->buildGitFoxScriptContentTest('install', '{{')) && p() && e('0'); // 步骤 15
r($gitfoxZenTest->buildGitFoxScriptContentTest('install', '"${GITFOX_URL}"')) && p() && e('1'); // 步骤 16
r($gitfoxZenTest->buildGitFoxScriptContentTest('install', 'if not exist "%INSTALL_DIR%"', 'WINNT', 'AMD64')) && p() && e('1'); // 步骤 17
r($gitfoxZenTest->buildGitFoxScriptInstallDirTest('install', 'WINNT', 'AMD64')) && p() && e('1'); // 步骤 18
r($gitfoxZenTest->buildGitFoxScriptExtensionTest('install', 'Windows', 'AMD64')) && p() && e('bat'); // 步骤 19
r($gitfoxZenTest->buildGitFoxScriptExtensionTest('install', 'FreeBSD', 'x86_64')) && p() && e('sh'); // 步骤 20
