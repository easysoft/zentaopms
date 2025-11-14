#!/usr/bin/env php
<?php

/**

title=测试 metricModel::getScopePairs();
timeout=0
cid=17126

- 获取全部scope的数量 @7
- 第一个scope的value第0条的value属性 @project
- 第一个scope的label第0条的label属性 @项目
- 获取部分scope的数量 @5
- 第二个scope的value第1条的value属性 @product
- 最后一个scope的value第6条的value属性 @program
- 参数为false时第一个scope的label第0条的label属性 @项目

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/calc.unittest.class.php';

$metric = new metricTest();

r(count($metric->getScopePairs())) && p() && e('7');                    // 获取全部scope的数量
r($metric->getScopePairs()) && p('0:value') && e('project');            // 第一个scope的value
r($metric->getScopePairs()) && p('0:label') && e('项目');                // 第一个scope的label
r(count($metric->getScopePairs(false))) && p() && e('5');               // 获取部分scope的数量
r($metric->getScopePairs()) && p('1:value') && e('product');            // 第二个scope的value
r($metric->getScopePairs()) && p('6:value') && e('program');            // 最后一个scope的value
r($metric->getScopePairs(false)) && p('0:label') && e('项目');           // 参数为false时第一个scope的label