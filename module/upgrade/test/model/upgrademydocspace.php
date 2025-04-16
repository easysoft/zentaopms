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

$upgrade->upgradeMyDocSpace();

global $tester;

$myDocLibs = $tester->dao->select('*')->from('zt_doclib')->where('type')->eq('mine')->fetchAll('id');

r(count($beforeUpgrade)) && p() && e(5);  // 获取更新前我的文档库数量。
r(count($myDocLibs))     && p() && e(8);  // 获取更新后我的文档库数量。
r($myDocLibs[6])         && p('id,main,vision,parent') && e('6,0,rnd,11');    // 获取更新后我的文档库 6 的信息
r($myDocLibs[7])         && p('id,main,vision,parent') && e('7,0,rnd,11');    // 获取更新后我的文档库 6 的信息
r($myDocLibs[8])         && p('id,main,vision,parent') && e('8,0,or,13');     // 获取更新后我的文档库 6 的信息
r($myDocLibs[9])         && p('id,main,vision,parent') && e('9,0,or,13');     // 获取更新后我的文档库 6 的信息
r($myDocLibs[10])        && p('id,main,vision,parent') && e('10,0,lite,12');  // 获取更新后我的文档库 6 的信息
r($myDocLibs[11])        && p('id,main,vision,parent') && e('11,1,rnd,0');    // 获取更新后我的文档库 6 的信息
r($myDocLibs[12])        && p('id,main,vision,parent') && e('12,1,lite,0');   // 获取更新后我的文档库 6 的信息
r($myDocLibs[13])        && p('id,main,vision,parent') && e('13,1,or,0');     // 获取更新后我的文档库 6 的信息