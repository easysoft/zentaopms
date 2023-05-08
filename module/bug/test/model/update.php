#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
su('admin');

function initData()
{
    $data = zdTable('bug');
    $data->id->range('1-5');
    $data->product->range('1-5');
    $data->branch->range('0-1');
    $data->project->range('0-5');
    $data->execution->range('0-5');
    $data->title->prefix("BUG")->range('1-5');
    $data->openedBuild->range('1-5');
    $data->type->range("[codeerror]");
    $data->status->range("[active]");
    $data->pri->range("[3]");
    $data->severity->range("[3]");

    $data->gen(4);
}

/**

title=bugModel->update();
timeout=0
cid=1

- 执行bug模块的updateObject方法，参数是$bugIdList[0], $t_uptitle
 - 第0条的field属性 @title
 - 第0条的old属性 @BUG1
 - 第0条的new属性 @john

- 执行bug模块的updateObject方法，参数是$bugIdList[0], $t_uptype
 - 第0条的field属性 @type
 - 第0条的old属性 @codeerror
 - 第0条的new属性 @config

- 执行bug模块的updateObject方法，参数是$bugIdList[0], $t_untitle @没有数据更新

- 执行bug模块的updateObject方法，参数是$bugIdList[0], $t_untype @没有数据更新



*/

initData();

$bugIdList = array('1', '2');

$t_uptitle   = array('title' => 'john');
$t_uptype    = array('type'  => 'config');
$t_untitle   = array('title' => 'john');
$t_untype    = array('type'  => 'config');

global $tester;
$tester->loadModel('bug');
r($tester->bug->updateObject($bugIdList[0], $t_uptitle))   && p('0:field,old,new') && e('title,BUG1,john');       // 测试更新bug名称
r($tester->bug->updateObject($bugIdList[0], $t_uptype))    && p('0:field,old,new') && e('type,codeerror,config'); // 测试更新bug类型
r($tester->bug->updateObject($bugIdList[0], $t_untitle))   && p()                  && e('没有数据更新');          // 测试不更改bug名称
r($tester->bug->updateObject($bugIdList[0], $t_untype))    && p()                  && e('没有数据更新');          // 测试不更改bug类型
