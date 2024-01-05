#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->deleteGeneralReportBlock();
cid=1

- 瀑布通用报表块已经被删除。@0
- 瀑布通用报表块配置已经被删除。@0

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';

$upgrade = new upgradeTest();

zdTable('config')->config('block_config')->gen(1);
zdTable('block')->config('waterfall_block')->gen(1);

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

r(empty($block)) && p() && e('1');                                                 //瀑布通用报表块已经被删除。
r(strpos($config->value, ',project|waterfallgeneralreport')) && p('') && e('0');   //瀑布通用报表块配置已经被删除。
