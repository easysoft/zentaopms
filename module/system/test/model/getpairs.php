#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');
zenData('system')->gen(10);

/**

title=测试 systemModel::getPairs();
timeout=0
cid=18737

- 查询默认键值对属性1 @应用1
- 查询默认键值对数量 @10
- 查询非集成应用属性1 @应用1
- 查询集成应用属性2 @应用2
- 查询集成应用数量 @5

*/
global $tester;
$system = $tester->loadModel('system');

r($system->getPairs())              && p('1') && e('应用1'); // 查询默认键值对
r(count($system->getPairs()))       && p()    && e('10');    // 查询默认键值对数量
r($system->getPairs(1, '0'))        && p('1') && e('应用1'); // 查询非集成应用
r($system->getPairs(2, '1'))        && p('2') && e('应用2'); // 查询集成应用
r(count($system->getPairs(0, '1'))) && p()    && e('5');     // 查询集成应用数量
