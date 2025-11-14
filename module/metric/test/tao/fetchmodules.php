#!/usr/bin/env php
<?php

/**

title=fetchModules
timeout=0
cid=17174

- 获取范围为系统的分组
 - 第0条的object属性 @application
 - 第0条的purpose属性 @scale
- 获取范围为产品的分组
 - 第0条的object属性 @bug
 - 第0条的purpose属性 @rate
- 获取范围为项目的分组
 - 第0条的object属性 @bug
 - 第0条的purpose属性 @scale
- 获取范围为执行的分组
 - 第0条的object属性 @bug
 - 第0条的purpose属性 @scale
- 获取范围为用户的分组
 - 第0条的object属性 @bug
 - 第0条的purpose属性 @scale

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/calc.unittest.class.php';

$metric = new metricTest();

r($metric->fetchModules('system'))    && p('0:object,purpose') && e('application,scale'); // 获取范围为系统的分组
r($metric->fetchModules('product'))   && p('0:object,purpose') && e('bug,rate');          // 获取范围为产品的分组
r($metric->fetchModules('project'))   && p('0:object,purpose') && e('bug,scale');         // 获取范围为项目的分组
r($metric->fetchModules('execution')) && p('0:object,purpose') && e('bug,scale');         // 获取范围为执行的分组
r($metric->fetchModules('user'))      && p('0:object,purpose') && e('bug,scale');         // 获取范围为用户的分组
