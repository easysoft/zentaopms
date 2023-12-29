#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->installIPD().
cid=1

- 开源18.2版本低于18.5版本，需要安装IPD，所以应该生成日志文件。@1
- 开源18.6版本高于18.5版本，不需要安装IPD，所以不应该生成日志文件。@0
- pro版本需要安装IPD，所以应该生成日志文件。@1
- 企业版8.2版本低于企业版8.5需要安装IPD，所以应该生成日志文件。@1
- 企业版8.6版本高于企业版8.5不需要安装IPD，所以不应该生成日志文件。@0
- 旗舰版4.2版本低于旗舰版4.5需要安装IPD，所以应该生成日志文件。@1
- 旗舰版4.6版本高于旗舰版4.5不需要安装IPD，所以不应该生成日志文件。@0

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';

$upgrade = new upgradeTest();

global $app;
$logFile = $app->getTmpRoot() . 'log/upgrade.' . date('Ymd') . '.log.php';
$upgradeSqlLines = $app->getTmpRoot() . 'upgradeSqlLines';

function initLogFile($logFile, $upgradeSqlLinesFile)
{
    if(file_exists($logFile)) unlink($logFile);
    if(file_exists($upgradeSqlLinesFile)) unlink($upgradeSqlLinesFile);
    file_put_contents($upgradeSqlLinesFile, '0-0');
}


$versionList = array('18.2', '18.6', 'pro', 'biz8.2', 'biz8.6', 'max4.2', 'max4.6', 'ipd1.0.beta1');

initLogFile($logFile, $upgradeSqlLines);
$upgrade->installIPDTest($versionList[0]);
r(file_exists($logFile)) && p('') && e(1);  //开源18.2版本低于18.5版本，需要安装IPD，所以应该生成日志文件


initLogFile($logFile, $upgradeSqlLines);
$upgrade->installIPDTest($versionList[1]);
r(file_exists($logFile)) && p('') && e(0);  //开源18.6版本高于18.5版本，不需要安装IPD，所以不应该生成日志文件

initLogFile($logFile, $upgradeSqlLines);
$upgrade->installIPDTest($versionList[2]);
r(file_exists($logFile)) && p('') && e(1);  //pro版本需要安装IPD，所以应该生成日志文件

initLogFile($logFile, $upgradeSqlLines);
$upgrade->installIPDTest($versionList[3]);
r(file_exists($logFile)) && p('') && e(1);  //企业版8.2版本低于企业版8.5需要安装IPD，所以应该生成日志文件

initLogFile($logFile, $upgradeSqlLines);
$upgrade->installIPDTest($versionList[4]);
r(file_exists($logFile)) && p('') && e(0);  //企业版8.6版本高于企业版8.5不需要安装IPD，所以不应该生成日志文件

initLogFile($logFile, $upgradeSqlLines);
$upgrade->installIPDTest($versionList[5]);
r(file_exists($logFile)) && p('') && e(1);  //旗舰版4.2版本低于旗舰版4.5需要安装IPD，所以应该生成日志文件

initLogFile($logFile, $upgradeSqlLines);
$upgrade->installIPDTest($versionList[6]);
r(file_exists($logFile)) && p('') && e(0);  //旗舰版4.6版本高于旗舰版4.5不需要安装IPD，所以不应该生成日志文件
