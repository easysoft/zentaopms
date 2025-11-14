#!/usr/bin/env php
<?php
/**

title=测试 hostModel->getGroupTreemap();
timeout=0
cid=16755

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
- 测试获取的分组拓扑图数据是否正确。
 - 第0条的text属性 @这是一个模块32
 - 第1条的text属性 @主机12
- 测试获取的分组拓扑图数据是否正确。
 - 属性text @主机1
 - 属性hostid @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('user')->gen(10);
zenData('serverroom')->gen(100);
zenData('module')->loadYaml('module')->gen(100)->fixPath();
zenData('host')->loadYaml('host')->gen(30);
zenData('lang')->gen(0);
su('admin');

global $tester;
$tester->loadModel('host');

$result = $tester->host->getGroupTreemap();
r($result['children'])                               && p('0:text;1:text;2:text') && e('这是一个模块1,这是一个模块51,这是一个模块61'); // 测试获取的分组拓扑图数据是否正确。
r($result['children'][0]['children'])                && p('0:text;1:text;2:text') && e('这是一个模块11,这是一个模块12,主机1');         // 测试获取的分组拓扑图数据是否正确。
r($result['children'][0]['children'][0]['children']) && p('0:text;1:text')        && e('这是一个模块31,主机11');                       // 测试获取的分组拓扑图数据是否正确。
r($result['children'][0]['children'][1]['children']) && p('0:text;1:text')        && e('这是一个模块32,主机12');                       // 测试获取的分组拓扑图数据是否正确。
r($result['children'][0]['children'][2])             && p('text,hostid')          && e('主机1,1');                                     // 测试获取的分组拓扑图数据是否正确。
