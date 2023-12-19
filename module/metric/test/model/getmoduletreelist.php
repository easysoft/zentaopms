#!/usr/bin/env php
<?php

/**

title=getModuleTreeList
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';
su('admin');

$metric = new metricTest();

r($metric->getModuleTreeList('system'))    && p('0:object,purpose;1:object,purpose;2:object,purpose') && e('program,scale,line,scale,product,scale');      // 测试范围为system的模块树
r($metric->getModuleTreeList('project'))   && p('0:object,purpose;1:object,purpose;2:object,purpose') && e('project,time,execution,scale,story,scale');    // 测试范围为project的模块树
r($metric->getModuleTreeList('product'))   && p('0:object,purpose;1:object,purpose;2:object,purpose') && e('productplan,scale,release,scale,story,scale'); // 测试范围为product的模块树
r($metric->getModuleTreeList('execution')) && p('0:object,purpose;1:object,purpose;2:object,purpose') && e('story,scale,story,rate,task,scale');           // 测试范围为execution的模块树
r($metric->getModuleTreeList('user'))      && p('0:object,purpose;1:object,purpose;2:object,purpose') && e('story,scale,task,scale,bug,scale');            // 测试范围为user的模块树
