#!/usr/bin/env php
<?php
/**

title=测试 hostModel->getGroupTreemap();
timeout=0
cid=1

- 测试获取的分组拓扑图数据是否正确。
 - 第0条的text属性 @这是一个模块1
 - 第1条的text属性 @这是一个模块51
 - 第2条的text属性 @这是一个模块61
- 测试获取的分组拓扑图数据是否正确。
 - 第0条的text属性 @这是一个模块11
 - 第1条的text属性 @这是一个模块12
 - 第2条的text属性 @主机1
- 测试获取的分组拓扑图数据是否正确。
 - 第0条的text属性 @这是一个模块31
 - 第1条的text属性 @主机11

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('account')->gen(100);
zdTable('serverroom')->gen(100);
zdTable('module')->config('module')->gen(100)->fixPath();
zdTable('host')->config('host')->gen(30);
zdTable('lang')->gen(0);
su('admin');

global $tester;
$tester->loadModel('host');

r($tester->host->getGroupTreemap()['children']) && p('0:text;1:text;2:text') && e('这是一个模块1,这是一个模块51,这是一个模块61');        // 测试获取的分组拓扑图数据是否正确。
r($tester->host->getGroupTreemap()['children'][0]['children']) && p('0:text;1:text;2:text') && e('这是一个模块11,这是一个模块12,主机1'); // 测试获取的分组拓扑图数据是否正确。
r($tester->host->getGroupTreemap()['children'][0]['children'][0]['children']) && p('0:text;1:text') && e('这是一个模块31,主机11');       // 测试获取的分组拓扑图数据是否正确。
