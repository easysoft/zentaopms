#!/usr/bin/env php
<?php

/**

title=getListByFilter
timeout=0
cid=1

- 执行metric模块的getListByFilter方法，参数是$filters1 
 - 第0条的code属性 @count_of_program
 - 第1条的code属性 @count_of_doing_program
- 执行metric模块的getListByFilter方法，参数是$filters1  @24
- 执行metric模块的getListByFilter方法，参数是$filters2 
 - 第0条的code属性 @count_of_story_in_execution
 - 第1条的code属性 @count_of_finished_story_in_execution
- 执行metric模块的getListByFilter方法，参数是$filters2  @15
- 执行metric模块的getListByFilter方法，参数是$filters3 
 - 第0条的code属性 @count_of_execution
 - 第1条的code属性 @count_of_wait_execution
- 执行metric模块的getListByFilter方法，参数是$filters3  @22
- 执行metric模块的getListByFilter方法，参数是$filters4 
 - 第0条的code属性 @count_of_program
 - 第1条的code属性 @count_of_doing_program
- 执行metric模块的getListByFilter方法，参数是$filters4, 'wait'  @0
- 执行metric模块的getListByFilter方法，参数是$filters4  @204

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';

$metric = new metricTest();

$filters1 = array('scope' => array('system', 'execution'), 'object' => array('program', 'execution'), 'purpose' => array('scale', 'cost'));
$filters2 = array('scope' => array('execution'));
$filters3 = array('object' => array('execution'));
$filters4 = array('purpose' => array('scale'));

r($metric->getListByFilter($filters1)) && p('0:code;1:code') && e('count_of_program,count_of_doing_program');
r(count($metric->getListByFilter($filters1))) && p('') && e(24);
r($metric->getListByFilter($filters2)) && p('0:code;1:code') && e('count_of_story_in_execution,count_of_finished_story_in_execution');
r(count($metric->getListByFilter($filters2))) && p('') && e(15);
r($metric->getListByFilter($filters3)) && p('0:code;1:code') && e('count_of_execution,count_of_wait_execution');
r(count($metric->getListByFilter($filters3))) && p('') && e(22);
r($metric->getListByFilter($filters4)) && p('0:code;1:code') && e('count_of_program,count_of_doing_program');
r($metric->getListByFilter($filters4, 'wait')) && p('') && e('0');
r(count($metric->getListByFilter($filters4))) && p('') && e(204);