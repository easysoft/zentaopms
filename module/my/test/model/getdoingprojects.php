#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/my.class.php';

zdTable('project')->gen('86');
zdTable('user')->gen('1');

su('admin');

/**

title=测试 myModel->getDoingProjects();
cid=1
pid=1

- 获取doingcount数据 @5
- 获取doing状态的项目
 - 第0条的name属性 @项目86
 - 第0条的status属性 @doing
- 获取doing状态的项目统计 @5

*/

$my = new myTest();

$doingCount = $my->getDoingProjectsTest()->doingCount;
$projects   = $my->getDoingProjectsTest()->projects;

r($doingCount)      && p()                && e('5');           //获取doingcount数据
r($projects)        && p('0:name,status') && e('项目86,doing');//获取doing状态的项目
r(count($projects)) && p()                && e('5');           //获取doing状态的项目统计
