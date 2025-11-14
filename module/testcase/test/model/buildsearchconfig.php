#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

zenData('product')->gen('45');
zenData('branch')->gen('10');
zenData('project')->gen('30');
zenData('story')->gen('10');
zenData('module')->gen('10');
zenData('user')->gen('1');

su('admin');

/**

title=测试 testcaseModel->buildSearchConfig();
cid=18965
pid=1

- 获取全部产品配置的module @testcase
- 获取全部产品配置的storyValues属性10 @10:软件需求10
- 获取全部产品配置的typeValues属性unit @单元测试
- 获取产品1配置的module @testcase
- 获取产品1配置的storyValues属性2 @2:软件需求2
- 获取产品1配置的typeValues属性config @配置相关
- 获取产品1主干配置的module @testcase
- 获取产品1主干配置的storyValues属性null @空
- 获取产品1主干配置的typeValues属性null @空

*/

$productID = array(0, 1);
$branch    = array('all', 0);

$testcase = new testcaseTest();

r($testcase->buildSearchConfigTest($productID[0], $branch[0])['module'])      && p()         && e('testcase');      // 获取全部产品配置的module
r($testcase->buildSearchConfigTest($productID[0], $branch[0])['storyValues']) && p('10')     && e('10:软件需求10'); // 获取全部产品配置的storyValues
r($testcase->buildSearchConfigTest($productID[0], $branch[0])['typeValues'])  && p('unit')   && e('单元测试');      // 获取全部产品配置的typeValues
r($testcase->buildSearchConfigTest($productID[1], $branch[0])['module'])      && p()         && e('testcase');      // 获取产品1配置的module
r($testcase->buildSearchConfigTest($productID[1], $branch[0])['storyValues']) && p('2')      && e('2:软件需求2');   // 获取产品1配置的storyValues
r($testcase->buildSearchConfigTest($productID[1], $branch[0])['typeValues'])  && p('config') && e('配置相关');      // 获取产品1配置的typeValues
r($testcase->buildSearchConfigTest($productID[1], $branch[1])['module'])      && p()         && e('testcase');      // 获取产品1主干配置的module
r($testcase->buildSearchConfigTest($productID[1], $branch[1])['storyValues']) && p('null')   && e('空');            // 获取产品1主干配置的storyValues
r($testcase->buildSearchConfigTest($productID[1], $branch[1])['typeValues'])  && p('null')   && e('空');            // 获取产品1主干配置的typeValues
