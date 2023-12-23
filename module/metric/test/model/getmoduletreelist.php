#!/usr/bin/env php
<?php

/**

title=getModuleTreeList
timeout=0
cid=1

- 测试范围为system的模块树
 - 第0条的object属性 @bug
 - 第0条的purpose属性 @rate
 - 第1条的object属性 @bug
 - 第1条的purpose属性 @scale
 - 第2条的object属性 @case
 - 第2条的purpose属性 @scale
- 测试范围为project的模块树
 - 第0条的object属性 @bug
 - 第0条的purpose属性 @scale
 - 第1条的object属性 @effort
 - 第1条的purpose属性 @hour
 - 第2条的object属性 @execution
 - 第2条的purpose属性 @scale
- 测试范围为product的模块树
 - 第0条的object属性 @bug
 - 第0条的purpose属性 @rate
 - 第1条的object属性 @bug
 - 第1条的purpose属性 @scale
 - 第2条的object属性 @case
 - 第2条的purpose属性 @scale
- 测试范围为execution的模块树
 - 第0条的object属性 @story
 - 第0条的purpose属性 @rate
 - 第1条的object属性 @story
 - 第1条的purpose属性 @scale
 - 第2条的object属性 @task
 - 第2条的purpose属性 @hour
- 测试范围为user的模块树
 - 第0条的object属性 @bug
 - 第0条的purpose属性 @scale
 - 第1条的object属性 @case
 - 第1条的purpose属性 @scale
 - 第2条的object属性 @story
 - 第2条的purpose属性 @scale

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';
su('admin');

$metric = new metricTest();

r($metric->getModuleTreeList('system'))    && p('0:object,purpose;1:object,purpose;2:object,purpose') && e('bug,rate,bug,scale,case,scale');         // 测试范围为system的模块树
r($metric->getModuleTreeList('project'))   && p('0:object,purpose;1:object,purpose;2:object,purpose') && e('bug,scale,effort,hour,execution,scale'); // 测试范围为project的模块树
r($metric->getModuleTreeList('product'))   && p('0:object,purpose;1:object,purpose;2:object,purpose') && e('bug,rate,bug,scale,case,scale');         // 测试范围为product的模块树
r($metric->getModuleTreeList('execution')) && p('0:object,purpose;1:object,purpose;2:object,purpose') && e('story,rate,story,scale,task,hour');      // 测试范围为execution的模块树
r($metric->getModuleTreeList('user'))      && p('0:object,purpose;1:object,purpose;2:object,purpose') && e('bug,scale,case,scale,story,scale');      // 测试范围为user的模块树