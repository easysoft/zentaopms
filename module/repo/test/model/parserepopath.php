#!/usr/bin/env php
<?php

/**

title=测试 repoModel::parseRepoPath();
timeout=0
cid=0

- 步骤1：普通 HTTP 地址替换到 GitFox 服务 @http://gitfox.local:3000/group/repo.git
- 步骤2：源地址带端口时仍保留路径 @http://gitfox.local:3000/group/repo
- 步骤3：已是 GitFox 地址时保持不变 @http://gitfox.local:3000/group/repo
- 步骤4：查询字符串不丢失 @http://gitfox.local:3000/group/repo?ref=main
- 步骤5：GitFox 端口为 80 时不追加端口号 @http://gitfox.local/group/repo.git

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

global $lang;
if(!isset($lang->codescan)) $lang->codescan = new stdclass();
if(!isset($lang->codescan->exec)) $lang->codescan->exec = 'exec';
if(!isset($lang->codescan->issue)) $lang->codescan->issue = 'issue';

$repoTest = new repoModelTest();
if(!isset($repoTest->instance->config->devops)) $repoTest->instance->config->devops = new stdclass();
$repoTest->instance->config->devops->gitfoxURL  = 'http://gitfox.local';
$repoTest->instance->config->devops->gitfoxPort = 3000;

r($repoTest->parseRepoPathTest('http://gitlab.example.com/group/repo.git'))       && p() && e('http://gitfox.local:3000/group/repo.git');
r($repoTest->parseRepoPathTest('https://gitlab.example.com:8443/group/repo'))     && p() && e('http://gitfox.local:3000/group/repo');
r($repoTest->parseRepoPathTest('http://gitfox.local:3000/group/repo'))            && p() && e('http://gitfox.local:3000/group/repo');
r($repoTest->parseRepoPathTest('https://gitlab.example.com/group/repo?ref=main')) && p() && e('http://gitfox.local:3000/group/repo?ref=main');
r(($repoTest->instance->config->devops->gitfoxPort = 80) ? $repoTest->parseRepoPathTest('http://gitlab.example.com/group/repo.git') : '') && p() && e('http://gitfox.local/group/repo.git');
