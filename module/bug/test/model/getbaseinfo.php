#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('user')->gen('1');

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

su('admin');

/**

title=测试 bugModel::getBaseInfo;
timeout=0
cid=1

- 获取ID等于2的bug
 - 属性pri @1
 - 属性type @codeerror

- 获取ID等于3的bug
 - 属性title @bug3
 - 属性status @active

- 获取ID等于4的bug
 - 属性severity @3
 - 属性openedBuild @trunk

- 获取不存在的bug属性title @0

*/

global $tester;
$tester->loadModel('bug');

r($tester->bug->getBaseInfo(2)) && p('pri,type')             && e('1,codeerror'); //获取ID等于2的bug
r($tester->bug->getBaseInfo(3)) && p('title,status')         && e('bug3,active'); //获取ID等于3的bug
r($tester->bug->getBaseInfo(4)) && p('severity,openedBuild') && e('3,trunk');     //获取ID等于4的bug
r($tester->bug->getBaseInfo(1)) && p('title')                && e('0');           //获取不存在的bug
