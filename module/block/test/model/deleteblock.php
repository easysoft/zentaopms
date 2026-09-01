#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

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
    $block->vision->range('rnd,lite');
    $block->dashboard->range('my');
    $block->module->range('project');
    $block->code->range('list,statistic');
    $block->title->prefix('区块名称')->range('2-5');

    $block->gen(4);
}

/**

title=测试 block 模块的update 方法
timeout=0
cid=15227

- 测试ID为2的区块是否存在属性id @2

- 测试ID为2的区块删除后的返回结果 @1

- 测试ID为2的区块的是否存在属性id @0

- 测试ID为3的区块的是否存在属性id @3

- 测试根据代号删除区块后的返回结果 @1

- 测试ID为3的区块的是否存在属性id @0

- 测试ID为22的区块删除后的返回结果 @1

*/

global $tester;
$tester->loadModel('block');

initData();

$blockTest = new blockModelTest();
r($tester->block->getByID(2)) && p('id') && e('2');            // 测试ID为2的区块是否存在
r($tester->block->deleteBlock(2, '', '')) && p('') && e('1');  // 测试ID为2的区块删除后的返回结果
r($tester->block->getByID(2)) && p('id') && e('0');            // 测试ID为2的区块的是否存在
r($tester->block->getByID(3)) && p('id') && e('3');            // 测试ID为3的区块的是否存在
r($tester->block->deleteBlock(0, 'project', 'statistic')) && p('') && e('1');  // 测试根据代号删除区块后的返回结果
r($tester->block->getByID(3)) && p('id') && e('0');    // 测试ID为3的区块的是否存在
r($tester->block->deleteBlock(22)) && p('') && e('1'); // 测试ID为22的区块删除后的返回结果
