#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
$zd_company = zenData('company');
$zd_company->id->range('1');
$zd_company->name->range('禅道软件');
$zd_company->admins->range('admin');
$zd_company->guest->range('0');
$zd_company->gen(1);
$dbh->exec("UPDATE zt_company SET admins = ',admin,' WHERE id = 1");
global $app;
$app->company = $dbh->query('SELECT * FROM zt_company WHERE id = 1')->fetch(PDO::FETCH_OBJ);

$zd_user = zenData('user');
$zd_user->id->range('1-1');
$zd_user->account->range('admin');
$zd_user->realname->range('admin');
$zd_user->password->range('e10adc3949ba59abbe56e057f20f883e');
$zd_user->visions->range('rnd');
$zd_user->deleted->range('0');
$zd_user->gen(1);

su('admin');

function initData()
{
    $block = zenData('block');
    $block->id->range('2-5');
    $block->account->range('admin');
    $block->dashboard->range('my');
    $block->module->range('welcome,guide,project,project');
    $block->code->range('welcome,guide,list,statistic');
    $block->title->prefix('区块名称')->range('1-4');
    $block->width->range('3,2,1,1');
    $block->height->range('3,3,6,6');
    $block->top->range('0,3,3,9');

    $block->gen(4);
}

/**

title=测试 blockModel->getByID();
timeout=0
cid=15225

- 测试新加入的区块宽度为2的时候，top是否为6。 @6
- 测试新加入的区块宽度为1的时候，top是否为15。 @15
- 测试新加入的区块宽度为3的时候，top是否为3。 @3
- width为0的时候，仍然是3 @3
- width为100的时候，仍然是3 @3

*/

global $tester;
$tester->loadModel('block');

initData();

$block = new stdclass();
$block->dashboard = 'my';
$block->width     = '2';
$block->vision    = 'rnd';

r($tester->block->computeBlockTop($block)) && p('') && e('6'); // 测试新加入的区块宽度为2的时候，top是否为6。

$block = new stdclass();
$block->dashboard = 'my';
$block->width     = '1';
$block->vision    = 'rnd';
r($tester->block->computeBlockTop($block)) && p('') && e('15'); // 测试新加入的区块宽度为1的时候，top是否为15。

$block = new stdclass();
$block->dashboard = 'my';
$block->width     = '3';
$block->vision    = 'rnd';
r($tester->block->computeBlockTop($block)) && p('') && e('3'); // 测试新加入的区块宽度为3的时候，top是否为3。

$block = new stdclass();
$block->dashboard = 'my';
$block->width     = '0';
$block->vision    = 'rnd';
r($tester->block->computeBlockTop($block)) && p('') && e('3'); // width为0的时候，仍然是3

$block = new stdclass();
$block->dashboard = 'my';
$block->width     = '100';
$block->vision    = 'rnd';
r($tester->block->computeBlockTop($block)) && p('') && e('3'); // width为100的时候，仍然是3