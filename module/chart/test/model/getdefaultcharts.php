#!/usr/bin/env php
<?php
/**

title=测试 chartModel::getDefaultCharts();
timeout=0
cid=15570

- 测试获取ID为0的图表 @0
- 测试获取一个不存在的ID 的图表 @0
- 测试获取宏观数据-一级项目集个数图表
 - 第0条的id属性 @33
 - 第0条的name属性 @图表33
- 测试获取宏观数据-一级项目集个数图表
 - 第0条的id属性 @0
 - 第0条的name属性 @0
- 测试获取宏观数据-一级项目集个数图表
 - 第0条的id属性 @45
 - 第0条的name属性 @图表45

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('chart')->loadYaml('chart')->gen(50);
zenData('module')->loadYaml('module')->gen(27)->fixPath();
zenData('user')->gen(5);

su('admin');
$chartTest = new chartModelTest();

r($chartTest->getDefaultChartsTest(0))     && p()            && e('0');         //测试获取ID为0的图表
r($chartTest->getDefaultChartsTest(10086)) && p()            && e('0');         //测试获取一个不存在的ID 的图表
r($chartTest->getDefaultChartsTest(33))    && p('0:id,name') && e('33,图表33'); //测试获取宏观数据-一级项目集个数图表
r($chartTest->getDefaultChartsTest(34))    && p('0:id,name') && e('0,0');       //测试获取宏观数据-一级项目集个数图表
r($chartTest->getDefaultChartsTest(35))    && p('0:id,name') && e('45,图表45'); //测试获取宏观数据-一级项目集个数图表
