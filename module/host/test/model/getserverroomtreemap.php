#!/usr/bin/env php
<?php
/**

title=测试 hostModel->getServerroomTreemap();
timeout=0
cid=1

- 测试获取的物理拓扑图数据是否正确。
 - 第0条的text属性 @北京
 - 第1条的text属性 @广东
 - 第2条的text属性 @杭州
- 测试获取的物理拓扑图数据是否正确。
 - 第0条的text属性 @这是机房名称1
 - 第1条的text属性 @这是机房名称4
 - 第2条的text属性 @这是机房名称7
- 测试获取的物理拓扑图数据是否正确。
 - 第0条的text属性 @这是机房名称3
 - 第1条的text属性 @这是机房名称6
 - 第2条的text属性 @这是机房名称9
- 测试获取的物理拓扑图数据是否正确。
 - 第0条的text属性 @主机1
 - 第0条的hostid属性 @1
- 测试获取的物理拓扑图数据是否正确。
 - 第0条的text属性 @主机4
 - 第0条的hostid属性 @4

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

r($tester->host->getServerroomTreemap()) && p('0:text;1:text;2:text') && e('北京,广东,杭州');                                           // 测试获取的物理拓扑图数据是否正确。
r($tester->host->getServerroomTreemap()[0]['children']) && p('0:text;1:text;2:text') && e('这是机房名称1,这是机房名称4,这是机房名称7'); // 测试获取的物理拓扑图数据是否正确。
r($tester->host->getServerroomTreemap()[1]['children']) && p('0:text;1:text;2:text') && e('这是机房名称3,这是机房名称6,这是机房名称9'); // 测试获取的物理拓扑图数据是否正确。
r($tester->host->getServerroomTreemap()[0]['children'][0]['children']) && p('0:text,hostid') && e('主机1,1');                           // 测试获取的物理拓扑图数据是否正确。
r($tester->host->getServerroomTreemap()[0]['children'][1]['children']) && p('0:text,hostid') && e('主机4,4');                           // 测试获取的物理拓扑图数据是否正确。
