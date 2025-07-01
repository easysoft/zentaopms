#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->deleteGeneralReportBlock();
cid=1

- 瀑布通用报表块已经被删除。 @1
- 瀑布通用报表块配置已经被删除。 @0
- 瀑布通用报表块配置已经被删除。 @0
- 瀑布通用报表块配置已经被删除。 @0
- 瀑布通用报表块配置已经被删除。 @0

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/upgrade.unittest.class.php';

$upgrade = new upgradeTest();

zenData('config')->loadYaml('block_config')->gen(1);
zenData('block')->loadYaml('waterfall_block')->gen(1);

$block = $tester->dao->select('*')->from('zt_block')->where('code')->eq('waterfallgeneralreport')->fetch();
if(isset($block->block)) $tester->dao->update('zt_block')->set('block')->eq('waterfallgeneralreport')->where('code')->eq('waterfallgeneralreport')->exec();

$upgrade->deleteGeneralReportBlock();

global $tester;

$block = $tester->dao->select('*')->from('zt_block')
    ->where('code')->eq('waterfallgeneralreport')
    ->fetch();

$config = $tester->dao->select('*')->from('zt_config')
    ->where('owner')->eq('system')
    ->andWhere('module')->eq('block')
    ->andWhere('key') ->eq('closed')
    ->fetch();

r(!isset($block->block) || empty($block))                    && p() && e('1');   //瀑布通用报表块已经被删除。
r(strpos($config->value, ',project|waterfallgeneralreport')) && p() && e('0');   //瀑布通用报表块配置已经被删除。
r(strpos($config->value, ',project|waterfallgeneral'))       && p() && e('0');   //瀑布通用报表块配置已经被删除。
r(strpos($config->value, ',project|waterfall'))              && p() && e('0');   //瀑布通用报表块配置已经被删除。
r(strpos($config->value, ',project|general'))                && p() && e('0');   //瀑布通用报表块配置已经被删除。
