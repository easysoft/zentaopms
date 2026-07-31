#!/usr/bin/env php
<?php

/**

title=测试 repoModel::parseRepoPath();
timeout=0
cid=0

- 步骤1：配置驱动的 GitFox 地址替换 @1
- 步骤2：源地址带端口时保留路径 @1
- 步骤3：配置地址路径保持正确 @1
- 步骤4：查询字符串不丢失 @1
- 步骤5：不同仓库路径保持正确 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

global $lang;
if(!isset($lang->codescan)) $lang->codescan = new stdclass();
if(!isset($lang->codescan->exec)) $lang->codescan->exec = 'exec';
if(!isset($lang->codescan->issue)) $lang->codescan->issue = 'issue';

$repoTest = new repoModelTest();

r($repoTest->parseRepoPathConfigTest('http://gitlab.example.com/group/repo.git'))       && p() && e('1');
r($repoTest->parseRepoPathConfigTest('https://gitlab.example.com:8443/group/repo'))     && p() && e('1');
r($repoTest->parseRepoPathConfigTest('http://gitfox.example.com/group/repo'))            && p() && e('1');
r($repoTest->parseRepoPathConfigTest('https://gitlab.example.com/group/repo?ref=main')) && p() && e('1');
r($repoTest->parseRepoPathConfigTest('http://gitlab.example.com/group/another-repo'))   && p() && e('1');
