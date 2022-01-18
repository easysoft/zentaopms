#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 programModel::getBudgetUnitList();
cid=1
pid=1

*/
global $app;
$app->loadConfig('project');
$app->loadLang('project');
$program = $tester->loadModel('program');
r($program->getBudgetUnitList()) && p() && e(''); //aaa
