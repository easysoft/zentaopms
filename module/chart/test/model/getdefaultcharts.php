#!/usr/bin/env php
<?php
/**

title=测试 chartModel::getDefaultCharts();
timeout=0
cid=1

- 测试传入的分组是子分组时，返回空数据 @0
- 测试查询不存在的分组 @0
- 获取组织分组下第一个图表的当前分组ID、ID、名称
 - 第0条的currentGroup属性 @45
 - 第0条的id属性 @45
 - 第0条的name属性 @图表45

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('chart')->config('chart')->gen(50);
zdTable('module')->config('module')->gen(27)->fixPath();
zdTable('user')->gen(5);
su('admin');

global $tester;
$chart = $tester->loadModel('chart');

r($chart->getDefaultCharts(36))  && p() && e('0');                //测试传入的分组是子分组时，返回空数据
r($chart->getDefaultCharts(123)) && p() && e('0');                //测试查询不存在的分组
r($chart->getDefaultCharts(35))  && p('0:currentGroup,id,name') && e('45,45,图表45'); //获取组织分组下第一个图表的当前分组ID、ID、名称
