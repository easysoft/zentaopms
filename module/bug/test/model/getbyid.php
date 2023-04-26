#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
su('admin');

function initData()
{
    $project = zdTable('bug');
    $project->id->range('2-5');
    $project->project->range('2-5');
    $project->title->prefix("bug")->range('2-5');
    $project->type->range("codeerror");
    $project->severity->range("3");
    $project->pri->range("1");
    $project->status->range("active");
    $project->openedBuild->range("trunk");

    $project->gen(4);
}

/**

title=测试 bugModel::getByID;
timeout=0
cid=1

- 执行bug模块的getByID方法，参数是2
 - 属性pri @1
 - 属性type @codeerror

- 执行bug模块的getByID方法，参数是1,属性title @0


*/

global $tester;
$tester->loadModel('bug');

initData();

r($tester->bug->getByID(2)) && p('pri,type') && e('1,codeerror'); //获取ID等于2的bug
r($tester->bug->getByID(1)) && p('title') && e('0');              //获取不存在的bug

