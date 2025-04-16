#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->upgradeMyDocSpace();
cid=1

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/upgrade.unittest.class.php';

zenData('doclib')->loadYaml('doclib')->gen(10);

$upgrade = new upgradeTest();

$beforeUpgrade = $tester->dao->select('*')->from('zt_doclib')->where('type')->eq('mine')->fetchAll('id');